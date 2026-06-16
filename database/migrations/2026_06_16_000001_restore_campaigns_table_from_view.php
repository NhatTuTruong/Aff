<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $viewExists = DB::selectOne("
            SELECT TABLE_TYPE AS type
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'campaigns'
        ");

        if (! $viewExists || strtoupper((string) $viewExists->type) !== 'VIEW') {
            return;
        }

        DB::statement('DROP VIEW IF EXISTS campaigns');

        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_id')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug');
            $table->enum('status', ['draft', 'active', 'paused'])->default('draft');
            $table->enum('type', ['coupon', 'key'])->default('coupon');
            $table->string('country')->nullable();
            $table->string('language')->default('en');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('intro')->nullable();
            $table->json('benefits')->nullable();
            $table->string('cta_text')->default('Get Deal Now');
            $table->text('affiliate_url');
            $table->string('link_network')->nullable();
            $table->string('email')->nullable();
            $table->string('coupon_code')->nullable();
            $table->boolean('coupon_enabled')->default(false);
            $table->string('template')->default('template1');
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('product_images')->nullable();
            $table->string('background_image')->nullable();
            $table->json('key_product_images')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['slug', 'deleted_at']);
        });

        DB::statement("
            INSERT INTO campaigns (
                id, brand_id, import_id, slug, status, type, title, subtitle, intro, benefits,
                cta_text, affiliate_url, link_network, email, coupon_code, coupon_enabled,
                template, logo, cover_image, product_images, background_image, key_product_images,
                created_at, updated_at, deleted_at
            )
            SELECT
                b.id,
                b.id,
                b.import_id,
                b.slug,
                CASE
                    WHEN b.status IN ('draft', 'active', 'paused') THEN b.status
                    ELSE 'active'
                END,
                CASE
                    WHEN b.type IN ('coupon', 'key') THEN b.type
                    ELSE 'coupon'
                END,
                COALESCE(NULLIF(b.title, ''), b.name),
                b.subtitle,
                b.intro,
                b.benefits,
                COALESCE(b.cta_text, 'Get Deal Now'),
                b.affiliate_url,
                b.link_network,
                b.email,
                b.coupon_code,
                COALESCE(b.coupon_enabled, 0),
                COALESCE(NULLIF(b.template, ''), 'template1'),
                b.logo,
                b.cover_image,
                b.product_images,
                b.background_image,
                b.key_product_images,
                b.created_at,
                b.updated_at,
                b.deleted_at
            FROM brands b
            WHERE b.affiliate_url IS NOT NULL AND TRIM(b.affiliate_url) <> ''
        ");
    }

    public function down(): void
    {
        if (! Schema::hasTable('campaigns')) {
            return;
        }

        Schema::drop('campaigns');

        DB::statement("
            CREATE VIEW campaigns AS
            SELECT
                b.id AS id,
                b.id AS brand_id,
                b.import_id AS import_id,
                b.slug AS slug,
                b.status AS status,
                b.type AS type,
                COALESCE(b.title, b.name) AS title,
                b.subtitle AS subtitle,
                b.intro AS intro,
                b.benefits AS benefits,
                b.cta_text AS cta_text,
                b.affiliate_url AS affiliate_url,
                b.link_network AS link_network,
                b.email AS email,
                b.coupon_code AS coupon_code,
                b.coupon_enabled AS coupon_enabled,
                b.template AS template,
                b.logo AS logo,
                b.cover_image AS cover_image,
                b.product_images AS product_images,
                b.background_image AS background_image,
                b.key_product_images AS key_product_images,
                b.created_at AS created_at,
                b.updated_at AS updated_at,
                b.deleted_at AS deleted_at
            FROM brands b
        ");
    }
};
