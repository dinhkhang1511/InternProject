<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use Closure;
use Illuminate\Http\Request;

class VerifiedShop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $shop = session('shop');
        if( !isset($shop) || empty($shop) || !Shop::find($shop->id))
        {
            return redirect()->route('shopify');
        }
        else
        {
            $request->attributes->add(['id' => $shop->id ]);
            return $next($request);
        }
    }
}
