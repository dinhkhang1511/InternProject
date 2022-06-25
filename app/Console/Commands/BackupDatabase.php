<?php

namespace App\Console\Commands;

use Exception;
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
        $i = 1;
        $id = 1;
        $columns = array('id','name','price','quantity','description');
        $this->info("Exporting products...");
        try
        {

            while(true)
            {
                // * mỗi vòng lặp lấy 100 products bỏ vào file csv sau đó truy vấn tới 100 products tiếp theo và lặp lại
                $products = DB::table('productsss')->orderBy('id')->where('id','>',$id)->limit(100)->get();
                if($products->count() == 0)
                    break;
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
                        $id = $product->id;
                    }
                    fclose($file);
                    $i++;
            }
            sleep(1);
            $this->info("Export successful!");
        }catch(Exception $ex)
        {
            $this->error("Something went wrong!");
            throw new $ex;
        }
    }
}
