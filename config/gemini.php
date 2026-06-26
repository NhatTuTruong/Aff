<?php

return [
    'api_key' => env('GEMINI_API_KEY'),
    'model' => env('GEMINI_MODEL', 'gemini-1.5-flash-latest'),
    'supported_models' => [
        'gemini-2.5-flash',
        'gemini-2.5-flash-lite',
        'gemini-3.1-flash-lite',
        'gemini-3.5-flash',
    ],
    /** Model hỗ trợ sinh ảnh (dùng cho blog auto: nội dung + ảnh đại diện 1 request) */
    'model_image' => env('GEMINI_MODEL_IMAGE', 'gemini-2.5-flash-image'),
    'timeout' => (int) env('GEMINI_TIMEOUT_SECONDS', 8),
];
