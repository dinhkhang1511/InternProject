<?php

namespace App\Http\Controllers;

use App\Jobs\WebhookProductCreate;
use App\Jobs\WebhookProductDelete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    //
    public function productCreate(Request $request)
    {
        $shop_name = $request->header('x-shopify-shop-domain');
        Log::info('Webhook product create');
        Log::info($request->all());
        WebhookProductCreate::dispatch($request->all(),$shop_name);
    }

    public function productUpdate(Request $request)
    {
        $shop_name = $request->header('x-shopify-shop-domain');
        Log::info('Webhook product update');
        Log::info($request->all());
        WebhookProductCreate::dispatch($request->all(),$shop_name);
    }

    public function productDelete(Request $request)
    {
        Log::info('Webhook product delete');
        Log::info($request->all());
        WebhookProductDelete::dispatch($request->all());
    }
}
