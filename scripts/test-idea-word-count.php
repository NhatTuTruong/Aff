<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$svc = new App\Services\GeminiBlogService();
$ref = new ReflectionClass($svc);
$extract = $ref->getMethod('extractWordCountFromIdea');
$extract->setAccessible(true);
$resolve = $ref->getMethod('resolveLengthInstruction');
$resolve->setAccessible(true);

$cases = [
    'Viết review 800 từ' => '800 words',
    'about 1500 words' => '1,500 words',
    '800-1000 words' => '800-1,000 words',
    'khoảng 600 từ' => '600 words',
    'no count here' => null,
];

$failed = 0;
foreach ($cases as $input => $expected) {
    $got = $extract->invoke($svc, $input);
    $ok = $got === $expected;
    if (! $ok) {
        $failed++;
    }
    echo ($ok ? 'OK' : 'FAIL')." | {$input} => ".($got ?? 'null')."\n";
}

$default = $resolve->invoke($svc, ['idea' => ''], '1,500-2,200 words');
echo ($default === '1,500-2,200 words' ? 'OK' : 'FAIL')." | default without idea => {$default}\n";

$fromIdea = $resolve->invoke($svc, ['idea' => 'Viết 900 từ'], '1,500-2,200 words');
echo ($fromIdea === '900 words' ? 'OK' : 'FAIL')." | resolve with idea => {$fromIdea}\n";

exit($failed > 0 ? 1 : 0);
