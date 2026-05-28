<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoAccountSeeder extends Seeder
{
    public function run()
    {
        $recordOwnerID = 'DEMO_COMPANY';
        $now = Carbon::now('Asia/Jakarta');

        // Create Demo Company
        DB::table('company')->updateOrInsert(
            ['KodePartner' => $recordOwnerID],
            [
                'NamaPartner' => 'Demo Company',
                'AlamatTagihan' => 'Jl. Demo No. 123',
                'NoTelp' => '081234567890',
                'Status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        );

        // Create Demo User
        DB::table('users')->updateOrInsert(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'RecordOwnerID' => $recordOwnerID,
                'created_at' => $now,
                'updated_at' => $now
            ]
        );

        // Demo Products
        $demoProducts = [
            [
                'KodeItem' => 'D001',
                'NamaItem' => 'Demo Product 1',
                'Barcode' => '111111',
                'HargaBeli' => 10000,
                'HargaJual' => 15000,
                'Stock' => 100,
                'Gambar' => 'demo1.jpg'
            ],
            [
                'KodeItem' => 'D002',
                'NamaItem' => 'Demo Product 2',
                'Barcode' => '222222',
                'HargaBeli' => 20000,
                'HargaJual' => 25000,
                'Stock' => 50,
                'Gambar' => 'demo2.jpg'
            ],
            [
                'KodeItem' => 'D003',
                'NamaItem' => 'Demo Product 3',
                'Barcode' => '333333',
                'HargaBeli' => 50000,
                'HargaJual' => 75000,
                'Stock' => 20,
                'Gambar' => 'demo3.jpg'
            ]
        ];

        foreach ($demoProducts as $product) {
            DB::table('itemmaster')->updateOrInsert(
                [
                    'KodeItem' => $product['KodeItem'],
                    'RecordOwnerID' => $recordOwnerID
                ],
                [
                    'NamaItem' => $product['NamaItem'],
                    'Barcode' => $product['Barcode'],
                    'HargaPokokPenjualan' => $product['HargaBeli'],
                    'HargaJual' => $product['HargaJual'],
                    'Stock' => $product['Stock'],
                    'Gambar' => $product['Gambar'],
                    'Active' => 'Y',
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            );
        }

        echo "Demo account and products created successfully!\n";
    }
}
