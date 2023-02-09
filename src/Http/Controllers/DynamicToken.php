<?php

namespace Studio1902\PeakTools\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DynamicToken extends Controller
{
     /**
     * Get refreshed CSRF token.
     *
     * @return string
     */
    public function __invoke(Request $request)
    {
        $referer = request()->headers->get('referer');
        $contains = str_contains($referer, request()->getHttpHost());
        if (empty($referer) || !$contains) {
            abort(404);
        }

        return response()->json([
            'csrf_token' => csrf_token()
        ]);
    }
}
