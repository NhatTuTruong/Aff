<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Support\AdminSettings;
use App\Support\AutoBlogSettings;

AdminSettings::set('auto_blog_global_idea', 'Viết bằng Tiếng Trung, có bảng so sánh');

$merged = AutoBlogSettings::mergeExtras([]);
$okMerge = ($merged['idea'] ?? '') === 'Viết bằng Tiếng Trung, có bảng so sánh';
echo ($okMerge ? 'OK' : 'FAIL')." | mergeExtras applies global idea\n";

$popupWins = AutoBlogSettings::mergeExtras(['idea' => 'Popup idea only']);
$okPopup = ($popupWins['idea'] ?? '') === 'Popup idea only';
echo ($okPopup ? 'OK' : 'FAIL')." | popup idea overrides global\n";

$svc = new App\Services\GeminiBlogService();
$ref = new ReflectionClass($svc);
$lang = $ref->getMethod('buildLanguageInstruction');
$lang->setAccessible(true);

$withIdea = $lang->invoke($svc, ['idea' => 'Viết bằng Tiếng Trung'], 'English');
$okLang = str_contains($withIdea, 'Follow the editor idea') || str_contains($withIdea, 'Theo ý tưởng');
echo ($okLang ? 'OK' : 'FAIL')." | language instruction defers to idea\n";

$withoutIdea = $lang->invoke($svc, ['idea' => ''], 'English');
$okDefault = str_contains($withoutIdea, 'English');
echo ($okDefault ? 'OK' : 'FAIL')." | default language when no idea\n";

AdminSettings::set('auto_blog_global_idea', '');

exit(($okMerge && $okPopup && $okLang && $okDefault) ? 0 : 1);
