<?php

use Illuminate\Support\Facades\Route;
use Studio1902\PeakTools\Http\Controllers\DynamicToken;

// Dynamic Token route for posting a form with Ajax.
Route::get('/dynamic-token/refresh', DynamicToken::class);
