<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // * Nên tạo 1 seed tên ProductSeeder để tạo dữ liệu mẫu nhưng do có 1 bảng nên em dùng seeder có sẵn
        $products_ = array('Áo thun' , 'Quần jean','Áo sơmi', 'Giày', 'Giày cao gót','Quần short','Áo ba lỗ','Nón');
        $status_ = ['pending','reject','approve'];
      //  dd(Arr::random($status_));
        foreach($products_ as $product)
        {
            DB::table('products')->insert([
                'name' => $product,
                'price' => rand(0, 99999),
                'quantity' => rand(0, 200),
                'status' => Arr::random($status_)
            ]);
        }
    }
}
