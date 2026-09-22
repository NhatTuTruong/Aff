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

$extract = $ref->getMethod('extractExplicitOutputLanguageFromIdea');
$extract->setAccessible(true);

$viIdeaOnly = $lang->invoke($svc, ['idea' => 'Review ngắn, nhấn mạnh shipping và FAQ'], 'English');
$okViIdeaEnglish = str_contains($viIdeaOnly, '**English**') && str_contains($viIdeaOnly, 'still write the article in English');
echo ($okViIdeaEnglish ? 'OK' : 'FAIL')." | Vietnamese idea text still outputs English\n";

$withChinese = $lang->invoke($svc, ['idea' => 'Viết bằng Tiếng Trung, có bảng so sánh'], 'English');
$okChinese = str_contains($withChinese, '**Chinese**') && str_contains($withChinese, 'explicitly requested');
echo ($okChinese ? 'OK' : 'FAIL')." | explicit Chinese in idea\n";

$explicitVi = $extract->invoke($svc, 'viết bằng tiếng Việt, thêm FAQ');
$okExplicitVi = $explicitVi === 'Vietnamese';
echo ($okExplicitVi ? 'OK' : 'FAIL')." | extract explicit Vietnamese\n";

$noExplicit = $extract->invoke($svc, 'So sánh giá và chất lượng sản phẩm');
$okNoExplicit = $noExplicit === null;
echo ($okNoExplicit ? 'OK' : 'FAIL')." | no language from Vietnamese topic only\n";

$thaiIdea = $lang->invoke($svc, ['idea' => 'viết bằng tiếng thái, thêm FAQ'], 'English');
$okThai = str_contains($thaiIdea, '**Thai**') && str_contains($thaiIdea, 'explicitly requested');
echo ($okThai ? 'OK' : 'FAIL')." | explicit Thai in idea (no PCRE error)\n";

$withoutIdea = $lang->invoke($svc, ['idea' => ''], 'English');
$okDefault = str_contains($withoutIdea, 'English');
echo ($okDefault ? 'OK' : 'FAIL')." | default language when no idea\n";

AdminSettings::set('auto_blog_global_idea', '');

exit(($okMerge && $okPopup && $okViIdeaEnglish && $okChinese && $okExplicitVi && $okNoExplicit && $okThai && $okDefault) ? 0 : 1);
