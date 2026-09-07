<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sectors REST API
    |--------------------------------------------------------------------------
    |
    | Configuration for Indonesian stock market and sector intelligence API.
    |
    */
    'api_key' => env('SECTORS_API_KEY', ''),
    'base_url' => env('SECTORS_API_BASE_URL', 'https://api.sectors.app/v1'),

    /*
    |--------------------------------------------------------------------------
    | AI Intelligence Engine
    |--------------------------------------------------------------------------
    |
    | Configuration for LLM reasoning (Google Gemini or OpenAI).
    |
    */
    'ai' => [
        'gemini_api_key' => env('GEMINI_API_KEY', ''),
        'gemini_model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        'openai_api_key' => env('OPENAI_API_KEY', ''),
    ],
];
