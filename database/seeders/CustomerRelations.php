<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerRelation;

class CustomerRelations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = fopen(database_path('seeders/data/customer_relations.csv'), 'r');
        
        // CSVのヘッダー行をスキップ
        fgetcsv($file);
        
            while (($row = fgetcsv($file)) !== FALSE) {
            // CSVの各列を変数に割り当て
            $data = [
                'received_at' => $row[1], // null
                'received_by_user_id' => $row[2],
                'reception_channel' => $row[3],// null
                'initial_content' => $row[4],// null
                'product_name' => $row[5],// null
                'customer_name' => $row[7],
                'contact_number' => $row[8],// null
                'link' => $row[9],// null
                'images' => $row[11],// null
                'needs_health_department_contact' => 0,
                'health_department_contact_details' => $row[11],// null
                'is_finished' => 1,
                // 他のフィールドがあれば追加
            ];
    
            // データをデータベースに挿入
            CustomerRelation::create($data);
        }
    }
}
