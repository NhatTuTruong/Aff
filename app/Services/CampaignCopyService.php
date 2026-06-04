<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class CampaignCopyService
{
    /**
     * @return array{succeeded: list<Campaign>, failed: list<array{campaign_id: int, title: string, message: string}>}
     */
    public function copyManyToUser(iterable $sourceCampaigns, User $targetUser): array
    {
        $succeeded = [];
        $failed = [];
        /** @var array<string, Brand> $brandCache normalized store name => brand created in this batch */
        $brandCache = [];
        /** @var array<string, int> $categoryCache normalized category name => category id on target user */
        $categoryCache = [];

        foreach ($sourceCampaigns as $sourceCampaign) {
            if (! $sourceCampaign instanceof Campaign) {
                continue;
            }

            try {
                $succeeded[] = $this->copyToUser($sourceCampaign, $targetUser, $brandCache, $categoryCache);
            } catch (\Throwable $e) {
                $failed[] = [
                    'campaign_id' => $sourceCampaign->id,
                    'title' => (string) $sourceCampaign->title,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return ['succeeded' => $succeeded, 'failed' => $failed];
    }

    /**
     * Copy campaign (and its store + coupons) to another user account.
     *
     * @param  array<string, Brand>  $brandCache  Reuse stores created earlier in the same bulk run (key = normalized name)
     * @param  array<string, int>  $categoryCache  Reuse categories created earlier in the same bulk run (key = normalized name)
     *
     * @throws RuntimeException
     */
    public function copyToUser(
        Campaign $sourceCampaign,
        User $targetUser,
        array &$brandCache = [],
        array &$categoryCache = [],
    ): Campaign {
        $sourceCampaign->loadMissing(['brand', 'couponItems']);
        $sourceBrand = $sourceCampaign->brand;

        if ($sourceBrand && $sourceBrand->category_id) {
            $sourceBrand->loadMissing('category');
        }

        if (! $sourceBrand) {
            throw new RuntimeException('Chiến dịch không có cửa hàng gắn kèm.');
        }

        $storeKey = $this->normalizeName($sourceBrand->name);

        if (isset($brandCache[$storeKey])) {
            return $this->copyCampaignToExistingBrand($sourceCampaign, $brandCache[$storeKey]);
        }

        if ($this->targetUserHasStore($targetUser->id, $sourceBrand->name)) {
            throw new RuntimeException(
                'Tài khoản đích đã có cửa hàng trùng tên «'.$sourceBrand->name.'». Không thể copy.'
            );
        }

        $newCampaign = DB::transaction(function () use ($sourceCampaign, $sourceBrand, $targetUser, &$categoryCache) {
            $targetCategoryId = $this->resolveTargetCategoryId($sourceBrand, $targetUser->id, $categoryCache);
            $targetBrand = $this->createTargetBrand($sourceBrand, $targetUser->id, $targetCategoryId);
            $newCampaign = $this->createTargetCampaign($sourceCampaign, $targetBrand->id);
            $this->copyCoupons($sourceCampaign, $newCampaign);

            return $newCampaign->fresh(['brand', 'couponItems']);
        });

        $brandCache[$storeKey] = $newCampaign->brand;

        return $newCampaign;
    }

    private function copyCampaignToExistingBrand(Campaign $sourceCampaign, Brand $targetBrand): Campaign
    {
        return DB::transaction(function () use ($sourceCampaign, $targetBrand) {
            $newCampaign = $this->createTargetCampaign($sourceCampaign, $targetBrand->id);
            $this->copyCoupons($sourceCampaign, $newCampaign);

            return $newCampaign->fresh(['brand', 'couponItems']);
        });
    }

    private function targetUserHasStore(int $targetUserId, string $storeName): bool
    {
        $normalized = $this->normalizeName($storeName);

        return Brand::query()
            ->where('user_id', $targetUserId)
            ->whereNull('deleted_at')
            ->get(['name'])
            ->contains(fn (Brand $brand) => $this->normalizeName($brand->name) === $normalized);
    }

    private function resolveTargetCategoryId(Brand $sourceBrand, int $targetUserId, array &$categoryCache = []): ?int
    {
        $categoryName = $this->sourceCategoryName($sourceBrand);

        if ($categoryName === null) {
            return null;
        }

        $cacheKey = $this->normalizeName($categoryName);

        if (isset($categoryCache[$cacheKey])) {
            return $categoryCache[$cacheKey];
        }

        $existing = Category::query()
            ->where('user_id', $targetUserId)
            ->whereNull('deleted_at')
            ->get(['id', 'name'])
            ->first(fn (Category $cat) => $this->normalizeName($cat->name) === $cacheKey);

        if ($existing) {
            $categoryCache[$cacheKey] = $existing->id;

            return $existing->id;
        }

        $sourceCategory = $sourceBrand->relationLoaded('category') ? $sourceBrand->getRelation('category') : null;
        if (! $sourceCategory instanceof Category && $sourceBrand->category_id) {
            $sourceCategory = Category::query()->find($sourceBrand->category_id);
        }

        $newCategory = Category::create([
            'user_id' => $targetUserId,
            'name' => $categoryName,
            'description' => $sourceCategory?->description,
            'is_active' => $sourceCategory?->is_active ?? true,
        ]);

        $categoryCache[$cacheKey] = $newCategory->id;

        return $newCategory->id;
    }

    private function sourceCategoryName(Brand $sourceBrand): ?string
    {
        if ($sourceBrand->category_id) {
            $related = $sourceBrand->relationLoaded('category')
                ? $sourceBrand->getRelation('category')
                : Category::query()->find($sourceBrand->category_id);

            if ($related instanceof Category && trim($related->name) !== '') {
                return trim($related->name);
            }
        }

        $legacy = $sourceBrand->getAttributes()['category'] ?? null;

        if (is_string($legacy) && trim($legacy) !== '') {
            return trim($legacy);
        }

        return null;
    }

    private function createTargetBrand(Brand $sourceBrand, int $targetUserId, ?int $categoryId): Brand
    {
        $categoryLabel = $this->sourceCategoryName($sourceBrand);

        return Brand::create([
            'user_id' => $targetUserId,
            'category_id' => $categoryId,
            'name' => $sourceBrand->name,
            'domain' => $sourceBrand->domain,
            'category' => $categoryLabel,
            'events' => $sourceBrand->events,
            'image' => $this->copyStoragePath($sourceBrand->image),
            'approved' => $sourceBrand->approved,
            'short_description' => $sourceBrand->short_description,
        ]);
    }

    private function createTargetCampaign(Campaign $source, int $targetBrandId): Campaign
    {
        $slugBase = $this->campaignSlugBase($source);

        return Campaign::create([
            'brand_id' => $targetBrandId,
            'import_id' => null,
            'slug' => Campaign::ensureUniqueCampaignSlug($slugBase),
            'status' => $source->status,
            'type' => $source->type,
            'title' => $source->title,
            'subtitle' => $source->subtitle,
            'intro' => $source->intro,
            'benefits' => $source->benefits,
            'cta_text' => $source->cta_text,
            'affiliate_url' => $source->affiliate_url,
            'link_network' => $source->link_network,
            'email' => $source->email,
            'coupon_code' => $source->coupon_code,
            'coupon_enabled' => $source->coupon_enabled,
            'template' => $source->template,
            'logo' => $this->copyStoragePath($source->logo),
            'cover_image' => $this->copyStoragePath($source->cover_image),
            'product_images' => $this->copyStoragePaths($source->product_images),
            'background_image' => $this->copyStoragePath($source->background_image),
            'key_product_images' => $this->copyStoragePaths($source->key_product_images),
        ]);
    }

    private function campaignSlugBase(Campaign $source): string
    {
        $raw = (string) $source->slug;
        $segments = array_values(array_filter(explode('/', $raw)));

        if (count($segments) >= 1) {
            return Str::slug(end($segments)) ?: Str::slug($source->title);
        }

        return Str::slug($source->title) ?: 'campaign';
    }

    private function copyCoupons(Campaign $source, Campaign $target): void
    {
        foreach ($source->couponItems as $coupon) {
            $target->couponItems()->create(
                $coupon->only(['code', 'offer', 'description', 'sort_order', 'starts_at', 'ends_at'])
            );
        }
    }

    private function copyStoragePath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $path = str_replace('\\', '/', ltrim($path, '/'));
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            return $path;
        }

        $directory = trim(dirname($path), '.');
        $filename = basename($path);
        $newPath = ($directory !== '' ? $directory.'/' : '').Str::uuid()->toString().'_'.$filename;

        $disk->copy($path, $newPath);

        return $newPath;
    }

    /**
     * @param  array<int, string>|string|null  $paths
     * @return array<int, string>|string|null
     */
    private function copyStoragePaths(array|string|null $paths): array|string|null
    {
        if ($paths === null || $paths === '') {
            return $paths;
        }

        if (is_string($paths)) {
            return $this->copyStoragePath($paths);
        }

        return array_values(array_filter(
            array_map(fn ($path) => $this->copyStoragePath(is_string($path) ? $path : null), $paths)
        ));
    }

    private function normalizeName(string $name): string
    {
        return mb_strtolower(trim($name));
    }
}
