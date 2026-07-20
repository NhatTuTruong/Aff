<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Support\BlogContentHtml;

$html = <<<'HTML'
<p>Intro</p>
<figure class="attachment">
<img src="/storage/blogs/content/image.png">
<figcaption class="attachment__caption"><span class="attachment__name">image.png</span><span class="attachment__size">5.14 MB</span></figcaption>
</figure>
<p><a href="/storage/blogs/content/image.png">image.png 5.14 MB</a></p>
<h2>Pros and Cons</h2>
HTML;

$result = BlogContentHtml::stripAttachmentCaptions($html);
$ok = ! str_contains($result, 'image.png 5.14 MB') && str_contains($result, 'Pros and Cons');

echo $ok ? "PASS\n" : "FAIL\n{$result}\n";
exit($ok ? 0 : 1);
