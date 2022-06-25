<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firegroup:Backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Database';

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
        DB::table('products')->orderBy('id')->chunk('100',function($products,$i=1){
            $columns = array('id','name','price','quantity','description');
            $file = fopen(storage_path('backup/'.date('d-m-Y')."-$i-backup.csv"),'w');
            fputcsv($file,$columns);
            foreach($products as $product)
            {
                $row['id'] = $product->id;
                $row['name'] = $product->name;
                $row['price'] = $product->price;
                $row['quantity'] = $product->quantity;
                $row['description'] = $product->description;
                fputcsv($file,array($row['id'],$row['name'],$row['price'],$row['quantity'],$row['description']));
            }
            fclose($file);
            $i++;
        });

        // echo "Getting products...\n";
        // $products = $products->toArray();
        // echo "Saving products...\n";
        // file_put_contents(storage_path('backup.txt'),json_encode($products));
        // echo "Command Successful\n";

        // $headers = array(
        //     "Content-type"         => "text/csv",
        //     "Content-Disposition"  => "attachment; filename=backup.csv",
        //     "Pragma"               => "no-cache",
        //     "Cache-Control"        => "must-revalidate, post-check=0, pre-check=0",
        //     "Expires"              => "0");

    }
}
