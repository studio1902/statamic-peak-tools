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
        if (!$this->isSafeRequest()) {
            abort(404);
        }

        return response()->json([
            'csrf_token' => csrf_token()
        ]);
    }

    protected function isSafeRequest(): bool
    {
        if (!($referer = request()->headers->get('referer'))) {
            return false;
        }

        if (in_array(config('app.env'), ['local'])) {
            return true;
        }

        return str_contains($referer, request()->getHttpHost());
    }
}
