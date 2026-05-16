<?php

namespace App\Filament\Admin\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.admin.pages.profile-settings';

    protected static ?string $navigationLabel = 'Hồ sơ cá nhân';

    protected static ?string $title = 'Hồ sơ cá nhân';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 999;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return (bool) Filament::auth()->check();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function mount(): void
    {
        $user = Filament::auth()->user();
        if (! $user instanceof User) {
            return;
        }

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'code' => $user->code,
            'avatar_path' => $user->avatar_path,
            'current_password' => null,
            'new_password' => null,
            'new_password_confirmation' => null,
        ]);
    }

    public function form(Form $form): Form
    {
        $isAdmin = fn (): bool => Filament::auth()->user() instanceof User
            && Filament::auth()->user()->isAdmin();

        return $form
            ->schema([
                Section::make('Thông tin tài khoản')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên hiển thị')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(fn (): bool => $isAdmin())
                            ->maxLength(255)
                            ->disabled(fn (): bool => ! $isAdmin())
                            ->dehydrated($isAdmin)
                            ->helperText(
                                fn (): ?string => $isAdmin()
                                    ? null
                                    : 'Chỉ quản trị viên mới được đổi email. Liên hệ admin nếu cần cập nhật.'
                            ),
                        TextInput::make('code')
                            ->label('Mã affiliate')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('—')
                            ->helperText('Dùng cho liên kết / theo dõi. Nút “Sao chép mã” ở góc trên bên phải.'),
                    ])
                    ->columns(1),
                Section::make('Đổi mật khẩu')
                    ->visible($isAdmin)
                    ->description('Chỉ điền khi bạn muốn đổi mật khẩu.')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Mật khẩu hiện tại')
                            ->password()
                            ->revealable()
                            ->autocomplete('current-password'),
                        TextInput::make('new_password')
                            ->label('Mật khẩu mới')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->autocomplete('new-password'),
                        TextInput::make('new_password_confirmation')
                            ->label('Xác nhận mật khẩu mới')
                            ->password()
                            ->revealable()
                            ->autocomplete('new-password'),
                    ])
                    ->columns(1),
                Section::make('Ảnh đại diện')
                    ->schema([
                        FileUpload::make('avatar_path')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('avatars')
                            ->disk('public')
                            ->maxSize(2048)
                            ->helperText('Kích thước tối đa 2MB.'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('copyAffiliateCode')
                ->label('Sao chép mã')
                ->icon('heroicon-o-clipboard-document')
                ->color('gray')
                ->visible(fn (): bool => filled(Filament::auth()->user()?->code))
                ->action('copyAffiliateCode'),
            Action::make('save')
                ->label('Lưu thay đổi')
                ->action('save'),
        ];
    }

    public function copyAffiliateCode(): void
    {
        $user = Filament::auth()->user();
        if (! $user instanceof User || empty($user->code)) {
            return;
        }

        $this->js('navigator.clipboard.writeText('.json_encode($user->code).')');

        Notification::make()
            ->title('Đã sao chép mã affiliate')
            ->success()
            ->send();
    }

    public function save(): void
    {
        $user = Filament::auth()->user();
        if (! $user instanceof User) {
            return;
        }

        $state = $this->form->getState();
        $userIsAdmin = $user->isAdmin();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];
        $attributes = [
            'name' => 'tên',
        ];
        if ($userIsAdmin) {
            $rules['email'] = ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)];
            $rules['current_password'] = ['nullable', 'required_with:new_password', 'current_password:web'];
            $rules['new_password'] = ['nullable', 'string', 'min:8', 'max:255', 'confirmed'];
            $attributes['email'] = 'email';
            $attributes['current_password'] = 'mật khẩu hiện tại';
            $attributes['new_password'] = 'mật khẩu mới';
        }

        Validator::make($state, $rules, [], $attributes)->validate();

        $user->name = $state['name'];
        if ($userIsAdmin) {
            $user->email = $state['email'];
        }
        $user->avatar_path = $state['avatar_path'] ?? null;

        if ($userIsAdmin && ! empty($state['new_password'])) {
            $user->password = Hash::make($state['new_password']);
        }

        $user->save();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'code' => $user->code,
            'avatar_path' => $user->avatar_path,
            'current_password' => null,
            'new_password' => null,
            'new_password_confirmation' => null,
        ]);

        Notification::make()
            ->title('Đã cập nhật hồ sơ')
            ->success()
            ->send();
    }
}
