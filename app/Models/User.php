<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Category;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** Danh mục mặc định cho user mới (affiliate) */
    public static function defaultCategoryNames(): array
    {
        return config('default_categories.names', [
            'Webhosting', 'Travel & Hotel', 'Shoes', 'Sports', 'Stationery', 'Skin Care', 'Pets',
            'Jewelry & Watches', 'Garden', 'Health & Beauty', 'Toys', 'Gifts & Flowers', 'Food & Beverages',
            'Event Planners', 'Electronics', 'Departmental', 'Car', 'Business', 'Books', 'Kids',
            'Accessories', 'Automotive', 'Aviation Assistance', 'Art & Crafts', 'Apparel & Clothing',
            'Tech', 'Home Accessories', 'Fitness',
        ]);
    }

    use HasFactory, Notifiable;
    
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->code)) {
                // Kiểm tra xem column code có tồn tại không (tránh lỗi khi migration chưa chạy)
                try {
                    do {
                        $code = str_pad((string) random_int(10000, 99999), 5, '0', STR_PAD_LEFT);
                    } while (\Illuminate\Support\Facades\DB::table('users')->where('code', $code)->exists());
                    
                    $user->code = $code;
                } catch (\Exception $e) {
                    // Nếu column chưa tồn tại, bỏ qua (migration sẽ xử lý)
                }
            }
        });

        static::created(function (User $user) {
            try {
                $names = self::defaultCategoryNames();
                $userCode = $user->code ?? '00000';
                foreach ($names as $name) {
                    Category::create([
                        'user_id' => $user->id,
                        'name' => $name,
                        'slug' => $userCode . '/' . \Illuminate\Support\Str::slug($name),
                        'is_active' => true,
                    ]);
                }
            } catch (\Exception $e) {
                report($e);
            }
        });
    }
}

