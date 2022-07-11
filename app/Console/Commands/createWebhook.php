<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class createWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopifyWebhook:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create webhook';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $shopName = $this->ask('What is shop name?');
        $shop = DB::table('shop')->where('name',$shopName)->first();
        $topic = $this->ask('What topic webhook you want to create?');
        // $topic = "products/update";
        if(!$shop)
        {
            $this->error('Your shop have not install app yet!');
            return;
        }

        // {
        //     "url": "https://your-development-store.myshopify.com/admin/api/2022-07/webhooks.json",
        //     "raw_url": "https://your-development-store.myshopify.com/admin/api/2022-07/webhooks.json",
        //     "method": "post",
        //     "headers": {
        //         "X-Shopify-Access-Token": "{access_token}",
        //         "Content-Type": "application/json"
        //     },
        //     "data": {
        //         "{\"webhook\":{\"topic\":\"orders/create\",\"address\":\"https://example.hostname.com/\",\"format\":\"json\",\"fields\":[\"id\",\"note\"]}}": ""
        //     }
        // }
        $url = "https://$shop->domain/admin/api/2022-07/webhooks.json";
        $headers=[
                    "X-Shopify-Access-Token"    => $shop->access_token,
                    "Content-Type"  => "application/json"
        ];
        $body =
        [
            "webhook" => [
                "topic" => $topic,
                "address" => "https://dee9-113-161-32-170.ap.ngrok.io/shopify/webhook",
                "format"    => "json",
            ]
        ];
        $response = Http::withHeaders($headers)->post("https://$shop->domain/admin/api/2022-07/webhooks.json",$body);
        if($response->successful())
        {
            echo(json_encode($response->json()));
        }else
            echo(json_encode($response->json()));
        return 0;
    }
}
