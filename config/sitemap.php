<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Static sitemap file (php artisan sitemap:generate)
    |--------------------------------------------------------------------------
    */
    'write_path' => public_path('sitemap.xml'),

    /*
    |--------------------------------------------------------------------------
    | Blog / review URLs in sitemap (canonical public paths)
    |--------------------------------------------------------------------------
    */
    'review_index_path' => '/review',
    'review_post_path' => '/review',
    'blogs_post_path' => '/blogs',

    'include_stores' => true,

];
