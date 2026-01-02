<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckArticleCredit
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->canWriteArticle()) {
            return redirect()->route('subscription.packages')
                ->with('error', 'Please purchase more credits to write articles');
        }

        return $next($request);
    }
} 