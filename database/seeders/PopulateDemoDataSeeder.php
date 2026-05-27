<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PopulateDemoDataSeeder extends Seeder
{
    public function run()
    {
        // Dynamic payload filter helper to make the seeder 100% robust against schema differences
        $filterPayload = function($table, $payload) {
            $columns = Schema::getColumnListing($table);
            return array_intersect_key($payload, array_flip($columns));
        };

        // ----------------------------------------------------
        // 1. UPDATE EMAIL DEMO HIBURAN (OLD ACCOUNT)
        // ----------------------------------------------------
        // "Untuk hiburan, pakaiakan akun lama, gor.servis@gmail.com hanya diubah emailnya menjadi email demo yang kita atur dilokal"
        $oldUser = DB::table('users')->where('email', 'gor.servis@gmail.com')->first();
        if ($oldUser) {
            DB::table('users')
                ->where('id', $oldUser->id)
                ->update([
                    'email' => 'gor.servicepos@pos.dstechsmart.com',
                    'email_verified_at' => Carbon::now(),
                    'Active' => 'Y'
                ]);
            // Pastikan perusahaannya aktif
            DB::table('company')
                ->where('KodePartner', $oldUser->RecordOwnerID)
                ->update([
                    'isActive' => 1,
                    'isInitialSetting' => 1,
                    'JenisUsaha' => 'Hiburan'
                ]);
        } else {
            // Jika akun lama tidak ditemukan (misal di lokal), pastikan akun gor demo lokal ada
            $gorUser = DB::table('users')->where('email', 'gor.servicepos@pos.dstechsmart.com')->first();
            if (!$gorUser) {
                // Buat perusahaan CL0010
                $companyPayload = $filterPayload('company', [
                    'KodePartner' => 'CL0010',
                    'NamaPartner' => 'Demo Hiburan & Sport Center',
                    'NamaPIC' => 'Demo Hiburan',
                    'AlamatTagihan' => '-',
                    'NoHP' => '081234567895',
                    'NoTlp' => '081234567895',
                    'NIKPIC' => '-',
                    'tempStore' => '',
                    'icon' => '',
                    'StartSubs' => Carbon::now()->toDateString(),
                    'EndSubs' => Carbon::now()->addYears(10)->toDateString(),
                    'ExtraDays' => 0,
                    'NPWP' => '-',
                    'TglPKP' => Carbon::now()->toDateString(),
                    'PPN' => 0,
                    'isHargaJualIncludePPN' => 0,
                    'isPostingAkutansi' => 0,
                    'NamaPosPrinter' => '',
                    'FooterNota' => '',
                    'JenisUsaha' => 'Hiburan',
                    'isActive' => 1,
                    'isInitialSetting' => 1,
                    'MaximalUser' => 999,
                    'KodePaketLangganan' => '2003',
                    'Email' => 'gor.servicepos@pos.dstechsmart.com',
                    'GudangPoS' => 'UMM',
                    'TerminBayarPoS' => '',
                    'TypeBackgraund' => 'Color',
                    'Backgraund' => '#ffffff',
                    'TypeKitchenBackgraund' => 'Color',
                    'KitchenBackgraund' => '#ffffff',
                    'QueueDesignSetting' => 'QueueManagement',
                    'CustDisplayDesignSetting' => 'default',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                DB::table('company')->updateOrInsert(
                    ['KodePartner' => 'CL0010'],
                    $companyPayload
                );

                DB::table('users')->updateOrInsert(
                    ['email' => 'gor.servicepos@pos.dstechsmart.com'],
                    [
                        'name' => 'Demo Hiburan Admin',
                        'password' => Hash::make('12345678'),
                        'Active' => 'Y',
                        'isConfirmed' => 1,
                        'RecordOwnerID' => 'CL0010',
                        'BranchID' => '',
                        'email_verified_at' => Carbon::now(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]
                );
            }
        }

        // ----------------------------------------------------
        // 1B. SEED TIKET MASUK (CL0010)
        // ----------------------------------------------------
        // Pastikan jenis item TIKET ada
        DB::table('jenisitem')->updateOrInsert(
            ['KodeJenis' => 'TIKET', 'RecordOwnerID' => 'CL0010'],
            [
                'NamaJenis' => 'Tiket Masuk',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        $tickets = [
            ['KodeItem' => 'TKT-DW', 'NamaItem' => 'Tiket Masuk Dewasa', 'HargaJual' => 50000, 'Gambar' => 'https://images.unsplash.com/photo-1572402230267-f3e267c1e5d2?w=500&auto=format&fit=crop&q=60'],
            ['KodeItem' => 'TKT-AN', 'NamaItem' => 'Tiket Masuk Anak', 'HargaJual' => 35000, 'Gambar' => 'https://images.unsplash.com/photo-1605144883445-6677f4efc4eb?w=500&auto=format&fit=crop&q=60'],
            ['KodeItem' => 'TKT-WK', 'NamaItem' => 'Tiket Masuk Weekend', 'HargaJual' => 75000, 'Gambar' => 'https://images.unsplash.com/photo-1541935661642-e1d8825832a8?w=500&auto=format&fit=crop&q=60']
        ];

        foreach ($tickets as $tkt) {
            $itemmasterPayload = $filterPayload('itemmaster', [
                'KodeItem' => $tkt['KodeItem'],
                'NamaItem' => $tkt['NamaItem'],
                'KodeJenisItem' => 'TIKET',
                'KodeMerk' => 'DSTech Ticket',
                'TypeItem' => 2, // Jasa/Tiket
                'Rak' => '-',
                'KodeGudang' => 'UMM',
                'KodeSupplier' => '-',
                'Satuan' => 'PCS',
                'Barcode' => $tkt['KodeItem'],
                'Gambar' => $tkt['Gambar'],
                'HargaPokokPenjualan' => 0,
                'HargaJual' => $tkt['HargaJual'],
                'HargaBeliTerakhir' => 0,
                'Stock' => 999999,
                'StockMinimum' => 0,
                'isKonsinyasi' => 'N',
                'Active' => 'Y',
                'AcctHPP' => '',
                'AcctPenjualan' => '',
                'AcctPenjualanJasa' => '',
                'AcctPersediaan' => '',
                'VatPercent' => 0,
                'TampilkanEMenu' => 0,
                'isFlashSale' => 'N',
                'FlashSalePrice' => 0,
                'isBestSeller' => 'N',
                'RecordOwnerID' => 'CL0010',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            DB::table('itemmaster')->updateOrInsert(
                ['KodeItem' => $tkt['KodeItem'], 'RecordOwnerID' => 'CL0010'],
                $itemmasterPayload
            );
        }

        // ----------------------------------------------------
        // 2. CONFIGURE DEMO RESTO & FNB ACCOUNT (CL0013)
        // ----------------------------------------------------
        $restoCompanyPayload = $filterPayload('company', [
            'KodePartner' => 'CL0013',
            'NamaPartner' => 'Demo Restaurant & Cafe Premium',
            'NamaPIC' => 'Demo Resto',
            'AlamatTagihan' => '-',
            'NoHP' => '081234567890',
            'NoTlp' => '081234567890',
            'NIKPIC' => '-',
            'tempStore' => '',
            'icon' => '',
            'StartSubs' => Carbon::now()->toDateString(),
            'EndSubs' => Carbon::now()->addYears(10)->toDateString(),
            'ExtraDays' => 0,
            'NPWP' => '-',
            'TglPKP' => Carbon::now()->toDateString(),
            'PPN' => 0,
            'isHargaJualIncludePPN' => 0,
            'isPostingAkutansi' => 0,
            'NamaPosPrinter' => '',
            'FooterNota' => '',
            'JenisUsaha' => 'FnB',
            'isActive' => 1,
            'isInitialSetting' => 1,
            'MaximalUser' => 999,
            'KodePaketLangganan' => 'PFNB003',
            'Email' => 'demoresto@pos.dstechsmart.com',
            'GudangPoS' => 'UMM',
            'TerminBayarPoS' => '',
            'TypeBackgraund' => 'Color',
            'Backgraund' => '#ffffff',
            'TypeKitchenBackgraund' => 'Color',
            'KitchenBackgraund' => '#ffffff',
            'QueueDesignSetting' => 'QueueManagement',
            'CustDisplayDesignSetting' => 'default',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('company')->updateOrInsert(
            ['KodePartner' => 'CL0013'],
            $restoCompanyPayload
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'demoresto@pos.dstechsmart.com'],
            [
                'name' => 'Demo Resto Admin',
                'password' => Hash::make('12345678'),
                'Active' => 'Y',
                'isConfirmed' => 1,
                'RecordOwnerID' => 'CL0013',
                'BranchID' => '',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        // Buat secondary alias demoresto@pos.dstrechsmart.com
        DB::table('users')->updateOrInsert(
            ['email' => 'demoresto@pos.dstrechsmart.com'],
            [
                'name' => 'Demo Resto Admin Alias',
                'password' => Hash::make('12345678'),
                'Active' => 'Y',
                'isConfirmed' => 1,
                'RecordOwnerID' => 'CL0013',
                'BranchID' => '',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        // ----------------------------------------------------
        // 3. CONFIGURE DEMO RETAIL ACCOUNT (CL0014)
        // ----------------------------------------------------
        $retailCompanyPayload = $filterPayload('company', [
            'KodePartner' => 'CL0014',
            'NamaPartner' => 'Demo Supermarket & Retail Modern',
            'NamaPIC' => 'Demo Retail',
            'AlamatTagihan' => '-',
            'NoHP' => '081234567891',
            'NoTlp' => '081234567891',
            'NIKPIC' => '-',
            'tempStore' => '',
            'icon' => '',
            'StartSubs' => Carbon::now()->toDateString(),
            'EndSubs' => Carbon::now()->addYears(10)->toDateString(),
            'ExtraDays' => 0,
            'NPWP' => '-',
            'TglPKP' => Carbon::now()->toDateString(),
            'PPN' => 0,
            'isHargaJualIncludePPN' => 0,
            'isPostingAkutansi' => 0,
            'NamaPosPrinter' => '',
            'FooterNota' => '',
            'JenisUsaha' => 'Retail',
            'PosTemplate' => 'NormalPoS_Premium',
            'isActive' => 1,
            'isInitialSetting' => 1,
            'MaximalUser' => 999,
            'KodePaketLangganan' => '2022',
            'Email' => 'demoretail@pos.dstechsmart.com',
            'GudangPoS' => 'UMM',
            'TerminBayarPoS' => '',
            'TypeBackgraund' => 'Color',
            'Backgraund' => '#ffffff',
            'TypeKitchenBackgraund' => 'Color',
            'KitchenBackgraund' => '#ffffff',
            'QueueDesignSetting' => 'QueueManagement',
            'CustDisplayDesignSetting' => 'default',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('company')->updateOrInsert(
            ['KodePartner' => 'CL0014'],
            $retailCompanyPayload
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'demoretail@pos.dstechsmart.com'],
            [
                'name' => 'Demo Retail Admin',
                'password' => Hash::make('12345678'),
                'Active' => 'Y',
                'isConfirmed' => 1,
                'RecordOwnerID' => 'CL0014',
                'BranchID' => '',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        // ----------------------------------------------------
        // 4. SEED 100 FNB PRODUCTS (CL0013)
        // ----------------------------------------------------
        // Clear existing items for clean seeding
        DB::table('itemmaster')->where('RecordOwnerID', 'CL0013')->delete();
        DB::table('menuheader')->where('RecordOwnerID', 'CL0013')->delete();

        $fnbImages = [
            'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1484723091739-30a097e8f929?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1473093290043-c9418f6624db?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&auto=format&fit=crop&q=60'
        ];

        $fnbCategories = [
            'Nasi & Karbo' => ['Nasi Goreng Special', 'Nasi Goreng Gila', 'Nasi Goreng Seafood', 'Nasi Goreng Babat', 'Nasi Goreng Keju', 'Nasi Timbel Komplit', 'Nasi Uduk Betawi', 'Nasi Kuning Tumpeng', 'Nasi Liwet Solo', 'Nasi Bakar Cakalang'],
            'Mie & Pasta' => ['Mie Goreng Jawa', 'Mie Goreng Seafood', 'Mie Aceh Spesial', 'Mie Nyemek Pedas', 'Bihun Goreng Ayam', 'Kwetiau Siram Sapi', 'Spaghetti Carbonara', 'Fettuccine Alfredo', 'Penne Arrabiata', 'Macaroni Schotel'],
            'Olahan Ayam' => ['Ayam Goreng Kalasan', 'Ayam Bakar Taliwang', 'Ayam Geprek Mozzarella', 'Ayam Betutu Bali', 'Ayam Pop Padang', 'Ayam Woku Manado', 'Chicken Katsu', 'Chicken Teriyaki', 'Ayam Penyet Sambal Ijo', 'Ayam Kremes Gurih'],
            'Daging & Sapi' => ['Rendang Sapi Minang', 'Sate Madura Asli', 'Sate Padang Pedas', 'Sate Maranggi Purwakarta', 'Empal Gentong Cirebon', 'Semur Sapi Betawi', 'Bistik Sapi Jawa', 'Sop Buntut Premium', 'Sop Iga Bakar Madu', 'Beef Yakiniku'],
            'Seafood Premium' => ['Gurame Asam Manis', 'Cumi Goreng Tepung', 'Udang Saus Padang', 'Kepiting Soka Crispy', 'Kerang Hijau Saus Tiram', 'Lobster Bakar Mentega', 'Kakap Merah Bakar', 'Cumi Bakar Kecap', 'Udang Bakar Madu', 'Gurame Bakar Rica'],
            'Snack & Dessert' => ['Roti Bakar Cokelat', 'Pisang Goreng Keju', 'Singkong Goreng Merekah', 'Cireng Rujak Pedas', 'French Fries Crispy', 'Onion Rings Gourmet', 'Pancake Strawberry', 'Waffle Ice Cream', 'Croissant Butter', 'Chocolate Lava Cake'],
            'Kopi & Teh' => ['Es Kopi Susu Gula Aren', 'Espresso Single Shot', 'Americano Ice', 'Cappuccino Hot', 'Cafe Latte Art', 'Caramel Macchiato', 'Es Teh Manis Jumbo', 'Es Teh Tarik Aceh', 'Lemon Tea Segar', 'Matcha Latte Premium'],
            'Jus & Mocktail' => ['Jus Alpukat Cokelat', 'Jus Mangga Harum Manis', 'Jus Strawberry Segar', 'Jus Jeruk Peras', 'Jus Buah Naga', 'Virgin Mojito Lime', 'Strawberry Sunrise Mocktail', 'Blue Lagoon Ocean', 'Caramel Milkshake', 'Vanilla Frappuccino'],
            'Sup & Soto' => ['Soto Ayam Lamongan', 'Soto Betawi Daging', 'Soto Kudus Mangkok', 'Soto Kediri Gurih', 'Sup Ayam Kampung', 'Sup Jamur Kuping', 'Bakso Urat Jumbo', 'Mie Bakso Malang', 'Batagor Bandung Riri', 'Pempek Palembang Asli'],
            'Sayur & Sehat' => ['Gado-Gado Betawi', 'Ketoprak Jakarta', 'Pecel Madiun Pedas', 'Karedok Sunda', 'Capcay Seafood', 'Cah Kangkung Belacan', 'Tumis Tauge Teri', 'Cah Brokoli Wortel', 'Sayur Asem Segar', 'Sayur Lodeh Spesial']
        ];

        $fnbIndex = 1;
        foreach ($fnbCategories as $category => $items) {
            foreach ($items as $name) {
                $code = '8991300000' . str_pad($fnbIndex, 4, '0', STR_PAD_LEFT);
                $price = rand(15, 120) * 1000;
                $cost = $price * 0.4;
                $image = $fnbImages[rand(0, 9)];

                $itemmasterPayload = $filterPayload('itemmaster', [
                    'KodeItem' => $code,
                    'NamaItem' => $name . ' Premium',
                    'KodeJenisItem' => $category,
                    'KodeMerk' => 'DSTech Resto',
                    'TypeItem' => 3, // Rakitan
                    'Rak' => '-',
                    'KodeGudang' => 'UMM',
                    'KodeSupplier' => '-',
                    'Satuan' => 'PCS',
                    'Barcode' => $code,
                    'Gambar' => $image,
                    'HargaPokokPenjualan' => $cost,
                    'HargaJual' => $price,
                    'HargaBeliTerakhir' => $cost,
                    'Stock' => 9999,
                    'StockMinimum' => 5,
                    'isKonsinyasi' => 'N',
                    'Active' => 'Y',
                    'AcctHPP' => '',
                    'AcctPenjualan' => '',
                    'AcctPenjualanJasa' => '',
                    'AcctPersediaan' => '',
                    'VatPercent' => 0,
                    'TampilkanEMenu' => 0,
                    'isFlashSale' => 'N',
                    'FlashSalePrice' => 0,
                    'isBestSeller' => 'N',
                    'RecordOwnerID' => 'CL0013',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                // Insert ke itemmaster
                DB::table('itemmaster')->insert($itemmasterPayload);

                $menuheaderPayload = $filterPayload('menuheader', [
                    'KodeItemHasil' => $code,
                    'QtyHasil' => 1,
                    'Satuan' => 'PCS',
                    'Gambar' => $image,
                    'HargaPokokStandar' => $cost,
                    'HargaJual' => $price,
                    'RecordOwnerID' => 'CL0013',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                // Insert ke menuheader
                DB::table('menuheader')->insert($menuheaderPayload);

                $fnbIndex++;
            }
        }

        // ----------------------------------------------------
        // 5. SEED 100 RETAIL PRODUCTS (CL0014)
        // ----------------------------------------------------
        // Clear existing items for clean seeding
        DB::table('itemmaster')->where('RecordOwnerID', 'CL0014')->delete();

        $retailImages = [
            'https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1608686207856-001b95cf60ca?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1607349913338-fca6f7fc42d0?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1528698827591-e19ccd7bc23d?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1563245372-f21724e3856d?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=500&auto=format&fit=crop&q=60',
            'https://images.unsplash.com/photo-1628102476625-88c44c9c8983?w=500&auto=format&fit=crop&q=60'
        ];

        $retailCategories = [
            'Sembako & Dapur' => ['Beras Pandan Wangi 5kg', 'Minyak Goreng Filma 2L', 'Gula Pasir Gulaku 1kg', 'Garam Dapur Cap Kapal', 'Kecap Manis Bango 550ml', 'Saus Sambal ABC 335ml', 'Tepung Terigu Segitiga Biru', 'Margarin Blue Band 200g', 'Santan Instan Kara 65ml', 'Madu Pramuka Asli 350ml'],
            'Makanan Instan' => ['Indomie Goreng Spesial', 'Indomie Kari Ayam', 'Pop Mie Baso', 'Sarimi Isi 2 Baso', 'Samyang Hot Chicken', 'Super Bubur Ayam', 'La Fonte Spaghetti', 'Kanzler Sosis Singlet', 'Fiesta Chicken Nugget', 'Bernardi Bakso Sapi'],
            'Susu & Olahan' => ['Susu UHT Ultra Milk 1L', 'Susu Kental Manis Frisian Flag', 'Susu Bubuk Dancow Fortigro', 'Susu Beruang Bear Brand', 'Keju Kraft Cheddar 165g', 'Yoghurt Cimory Squeeze', 'Yakult Pack Isi 5', 'Mentega Anchor 227g', 'Susu Almond Alpro 1L', 'Es Krim Walls Feast'],
            'Camilan & Snack' => ['Chitato Sapi Panggang', 'Lay\'s Rumput Laut', 'Pringles Potato Chips', 'Taro Net Seaweed', 'Oreo Vanilla 133g', 'Beng Beng Share It', 'Silverqueen Almond 62g', 'Cadbury Dairy Milk', 'Kacang Garuda Atom', 'Kusuka Keripik Singkong'],
            'Minuman Segar' => ['Coca Cola Pet 1.5L', 'Sprite Lemon 1.5L', 'Fanta Strawberry 1.5L', 'Teh Botol Sosro Kotak', 'Aqua Botol 600ml', 'Pocari Sweat 500ml', 'Buavita Jus Mangga 1L', 'Adem Sari Cingku', 'Kratingdaeng Botol', 'Yakult Single'],
            'Kesehatan & Diet' => ['Sari Gandum Sandwich', 'Fitbar Chocolate', 'Quaker Oats Instant 800g', 'Tropicana Slim Sweetener', 'Sereal Kellogg\'s Corn Flakes', 'Minyak Zaitun Bertolli', 'Chia Seeds Organik 100g', 'Soyjoy Almond Chocolate', 'Granola Bites Yummy', 'Teh Hijau Kepala Djenggot'],
            'Perawatan Diri' => ['Sabun Mandi Lifebuoy', 'Shampoo Pantene Anti Dandruff', 'Pasta Gigi Pepsodent', 'Sikat Gigi Colgate Medium', 'Sabun Wajah Biore Men\'s', 'Pond\'s White Beauty', 'Rexona Men Roll On', 'Nivea Body Lotion 200ml', 'Minyak Telon My Baby', 'Bedak Bayi Caladine'],
            'Kebutuhan Rumah' => ['Deterjen Rinso Anti Noda 770g', 'Pelembut Downy Sunrise', 'Sabun Cuci Piring Mama Lemon', 'Pembersih Lantai Super Pell', 'Bayclean Pemutih 500ml', 'Wipol Karbol Wangi', 'Hit Obat Nyamuk Semprot', 'Tissue Paseo 250 Sheets', 'Sponge Cuci Piring Scot-Brite', 'Sapu Lantai Nilon Premium'],
            'Kosmetik & Make Up' => ['Wardah Matte Lip Cream', 'Emina Bright Stuff', 'Maybelline Mascara Waterproof', 'Make Over Face Powder', 'Pixy Two Way Cake', 'Garnier Micellar Water', 'Safi White Expert Cream', 'Viva Air Mawar 100ml', 'Emina Sun Protection', 'Vaseline Petroleum Jelly'],
            'Alat Tulis & Kantor' => ['Buku Tulis Sinar Dunia 38l', 'Pulpen Kenko Gel 0.5mm', 'Pensil Faber Castell 2B', 'Penghapus Joyko Hitam', 'Penggaris Besi 30cm', 'Double Tape 3M', 'Kertas HVS A4 PaperOne', 'Map Plastik Folder', 'Stabilo Boss Original', 'Gunting Kertas Stainless']
        ];

        $retailIndex = 1;
        foreach ($retailCategories as $category => $items) {
            foreach ($items as $name) {
                $code = '8991400000' . str_pad($retailIndex, 4, '0', STR_PAD_LEFT);
                $price = rand(3, 250) * 1000;
                $cost = $price * 0.7;
                $image = $retailImages[rand(0, 9)];

                $itemmasterPayload = $filterPayload('itemmaster', [
                    'KodeItem' => $code,
                    'NamaItem' => $name,
                    'KodeJenisItem' => $category,
                    'KodeMerk' => 'DSTech Retail',
                    'TypeItem' => 1, // Inventory
                    'Rak' => '-',
                    'KodeGudang' => 'UMM',
                    'KodeSupplier' => '-',
                    'Satuan' => 'PCS',
                    'Barcode' => $code,
                    'Gambar' => $image,
                    'HargaPokokPenjualan' => $cost,
                    'HargaJual' => $price,
                    'HargaBeliTerakhir' => $cost,
                    'Stock' => rand(50, 1000),
                    'StockMinimum' => 10,
                    'isKonsinyasi' => 'N',
                    'Active' => 'Y',
                    'AcctHPP' => '',
                    'AcctPenjualan' => '',
                    'AcctPenjualanJasa' => '',
                    'AcctPersediaan' => '',
                    'VatPercent' => 0,
                    'TampilkanEMenu' => 0,
                    'isFlashSale' => 'N',
                    'FlashSalePrice' => 0,
                    'isBestSeller' => 'N',
                    'RecordOwnerID' => 'CL0014',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                // Insert ke itemmaster
                DB::table('itemmaster')->insert($itemmasterPayload);

                $retailIndex++;
            }
        }

        // ----------------------------------------------------
        // 6. SYNC ROLES, USER-ROLES & PERMISSION-ROLES FOR DEMO ACCOUNTS
        // ----------------------------------------------------
        $setupCompanyRolesAndPermissions = function($partnerCode, $email, $packageName) {
            // A. Ensure SuperAdmin role exists for this partner
            $roleId = DB::table('roles')
                ->where('RecordOwnerID', $partnerCode)
                ->where('RoleName', 'SuperAdmin')
                ->value('id');
                
            if (!$roleId) {
                $roleId = DB::table('roles')->insertGetId([
                    'RoleName' => 'SuperAdmin',
                    'RecordOwnerID' => $partnerCode
                ]);
            }
            
            // B. Ensure user has UserRole mapped to SuperAdmin
            $user = DB::table('users')->where('email', $email)->first();
            if ($user) {
                DB::table('userrole')->updateOrInsert(
                    ['userid' => $user->id, 'RecordOwnerID' => $partnerCode],
                    ['roleid' => $roleId]
                );
            }
            
            // C. Sync permissions from subscription package to permissionrole
            if (!empty($packageName)) {
                // Delete existing permissionrole for this role & partner
                DB::table('permissionrole')
                    ->where('roleid', $roleId)
                    ->where('RecordOwnerID', $partnerCode)
                    ->delete();
                    
                // Fetch all permissions from subscription details
                $subsDetails = DB::table('subscriptiondetail')
                    ->where('NoTransaksi', $packageName)
                    ->pluck('PermissionID');
                    
                $insertPayloads = [];
                foreach ($subsDetails as $permId) {
                    $insertPayloads[] = [
                        'roleid' => $roleId,
                        'permissionid' => $permId,
                        'RecordOwnerID' => $partnerCode
                    ];
                }
                
                if (!empty($insertPayloads)) {
                    DB::table('permissionrole')->insert($insertPayloads);
                }
            }
        };

        // Run mapping for the three demo companies
        $setupCompanyRolesAndPermissions('CL0010', 'gor.servicepos@pos.dstechsmart.com', '2003');
        $setupCompanyRolesAndPermissions('CL0013', 'demoresto@pos.dstechsmart.com', 'PFNB003');
        $setupCompanyRolesAndPermissions('CL0014', 'demoretail@pos.dstechsmart.com', '2022');

        // ----------------------------------------------------
        // 7. CONFIGURE DEMO APOTEK ACCOUNT (demoapotek)
        // ----------------------------------------------------
        // A. Setup Company and User
        $apotekCompanyPayload = $filterPayload('company', [
            'KodePartner' => 'demoapotek',
            'NamaPartner' => 'Demo Apotek & Klinik Medika',
            'NamaPIC' => 'Demo Apotek',
            'AlamatTagihan' => '-',
            'NoHP' => '081234567892',
            'NoTlp' => '081234567892',
            'NIKPIC' => '-',
            'tempStore' => '',
            'icon' => '',
            'StartSubs' => Carbon::now()->toDateString(),
            'EndSubs' => Carbon::now()->addYears(10)->toDateString(),
            'ExtraDays' => 0,
            'NPWP' => '-',
            'TglPKP' => Carbon::now()->toDateString(),
            'PPN' => 0,
            'isHargaJualIncludePPN' => 0,
            'isPostingAkutansi' => 0,
            'NamaPosPrinter' => '',
            'FooterNota' => '',
            'JenisUsaha' => 'Retail',
            'isActive' => 1,
            'isInitialSetting' => 1,
            'MaximalUser' => 999,
            'KodePaketLangganan' => '2022',
            'Email' => 'demoapotek@pos.dstechsmart.com',
            'GudangPoS' => 'UMM',
            'TerminBayarPoS' => '',
            'TypeBackgraund' => 'Color',
            'Backgraund' => '#ffffff',
            'TypeKitchenBackgraund' => 'Color',
            'KitchenBackgraund' => '#ffffff',
            'QueueDesignSetting' => 'QueueManagement',
            'CustDisplayDesignSetting' => 'default',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('company')->updateOrInsert(['KodePartner' => 'demoapotek'], $apotekCompanyPayload);

        DB::table('users')->updateOrInsert(
            ['email' => 'demoapotek@pos.dstechsmart.com'],
            [
                'name' => 'Demo Apotek Admin',
                'password' => Hash::make('12345678'),
                'Active' => 'Y',
                'isConfirmed' => 1,
                'RecordOwnerID' => 'demoapotek',
                'BranchID' => '',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        // B. Setup master data
        DB::table('gruppelanggan')->updateOrInsert(
            ['KodeGrup' => '1001', 'RecordOwnerID' => 'demoapotek'],
            ['NamaGrup' => 'UMM', 'LevelHarga' => 1, 'DiskonPersen' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );

        DB::table('pelanggan')->updateOrInsert(
            ['KodePelanggan' => 'CUST-UMUM', 'RecordOwnerID' => 'demoapotek'],
            [
                'NamaPelanggan' => 'Pelanggan Umum',
                'KodeGrupPelanggan' => '1001',
                'Status' => 1,
                'ProvID' => -1,
                'KotaID' => -1,
                'KelID' => -1,
                'KecID' => -1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        DB::table('gudang')->updateOrInsert(
            ['KodeGudang' => 'UMM', 'RecordOwnerID' => 'demoapotek'],
            ['NamaGudang' => 'Gudang Utama Apotek', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );

        DB::table('satuan')->updateOrInsert(
            ['KodeSatuan' => 'TABLET', 'RecordOwnerID' => 'demoapotek'],
            ['NamaSatuan' => 'Tablet', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );
        DB::table('satuan')->updateOrInsert(
            ['KodeSatuan' => 'BOTOL', 'RecordOwnerID' => 'demoapotek'],
            ['NamaSatuan' => 'Botol', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );
        DB::table('satuan')->updateOrInsert(
            ['KodeSatuan' => 'STRIP', 'RecordOwnerID' => 'demoapotek'],
            ['NamaSatuan' => 'Strip', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );

        DB::table('jenisitem')->updateOrInsert(
            ['KodeJenis' => 'OBAT_BEBAS', 'RecordOwnerID' => 'demoapotek'],
            ['NamaJenis' => 'Obat Bebas', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );
        DB::table('jenisitem')->updateOrInsert(
            ['KodeJenis' => 'OBAT_RESEP', 'RecordOwnerID' => 'demoapotek'],
            ['NamaJenis' => 'Obat Resep/Keras', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );
        DB::table('jenisitem')->updateOrInsert(
            ['KodeJenis' => 'ALKES', 'RecordOwnerID' => 'demoapotek'],
            ['NamaJenis' => 'Alat Kesehatan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );

        DB::table('merk')->updateOrInsert(
            ['KodeMerk' => 'GENERIK', 'RecordOwnerID' => 'demoapotek'],
            ['NamaMerk' => 'Obat Generik', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );
        DB::table('merk')->updateOrInsert(
            ['KodeMerk' => 'PATEN', 'RecordOwnerID' => 'demoapotek'],
            ['NamaMerk' => 'Obat Paten', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );

        DB::table('supplier')->updateOrInsert(
            ['KodeSupplier' => 'SP0001', 'RecordOwnerID' => 'demoapotek'],
            [
                'NamaSupplier' => 'PBF Kalbe Farma',
                'Status' => 1,
                'NoTlp1' => '081234567890',
                'Alamat' => '-',
                'Email' => '-',
                'ProvID' => -1,
                'KotaID' => -1,
                'KelID' => -1,
                'KecID' => -1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        DB::table('settingaccount')->updateOrInsert(
            ['RecordOwnerID' => 'demoapotek'],
            [
                'InvAcctHargaPokokPenjualan' => 5110001,
                'InvAcctPendapatanJual' => 4110001,
                'InvAcctPendapatanJasa' => 4110002,
                'InvAcctPersediaan' => 1310001,
                'InvAcctPendapatanNonInventory' => 4110003,
                'InvAcctPendapatanLainLain' => 4110003,
                'InvAcctPenyesuaiaanStockMasuk' => 7111001,
                'InvAcctPenyesuaiaanStockKeluar' => 8111001,
                'PbAcctPajakPembelian' => 1130001,
                'PbAcctPembayaranTunai' => 1110001,
                'PbAcctPembayaranNonTunai' => 1120001,
                'PbAcctHutang' => 2110001,
                'PbAcctUangMukaPembelian' => 1410001,
                'PjAcctPajakPenjualan' => 2130001,
                'PjAcctPenjualanTunai' => 1110001,
                'PjAcctPenjualanNonTunai' => 1120001,
                'PjAcctPiutang' => 1140001,
                'PjAcctUangMukaPenjualan' => 4120001,
                'PjAcctGoodsInTransit' => 1310002,
                'PjAcctReturnPenjualan' => 4120001,
                'PjAcctPajakHiburan' => 2130002,
                'KnAcctHutangKonsinyasi' => 2110001,
                'KnAcctPembayaranHutang' => 1110001,
                'KnAcctPenerimaanKonsinyasi' => 2110002,
                'OthAcctModal' => 3110001,
                'OthAcctPrive' => 3110004,
                'OthAcctLabaDitahan' => 3110002,
                'OthAcctLabaTahunBerjalan' => 3110003,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        // C. Seed Products
        $apotekProducts = [
            [
                'KodeItem' => 'APT-0001',
                'NamaItem' => 'Paracetamol 500mg Strip',
                'KodeJenisItem' => 'OBAT_BEBAS',
                'KodeMerk' => 'GENERIK',
                'Satuan' => 'STRIP',
                'HargaJual' => 8500,
                'HargaPokok' => 5000,
                'Barcode' => 'APT0001',
                'Gambar' => '/images/apotek/paracetamol.png',
                'Stock' => 250
            ],
            [
                'KodeItem' => 'APT-0002',
                'NamaItem' => 'Amoxicillin 500mg Strip',
                'KodeJenisItem' => 'OBAT_RESEP',
                'KodeMerk' => 'GENERIK',
                'Satuan' => 'STRIP',
                'HargaJual' => 15000,
                'HargaPokok' => 10000,
                'Barcode' => 'APT0002',
                'Gambar' => '/images/apotek/amoxicillin.png',
                'Stock' => 150
            ],
            [
                'KodeItem' => 'APT-0003',
                'NamaItem' => 'OBH Tropica Cough Syrup 100ml',
                'KodeJenisItem' => 'OBAT_BEBAS',
                'KodeMerk' => 'PATEN',
                'Satuan' => 'BOTOL',
                'HargaJual' => 22000,
                'HargaPokok' => 16000,
                'Barcode' => 'APT0003',
                'Gambar' => '/images/apotek/obh_cough_syrup.png',
                'Stock' => 80
            ],
            [
                'KodeItem' => 'APT-0004',
                'NamaItem' => 'Vitamin C 1000mg Tube',
                'KodeJenisItem' => 'OBAT_BEBAS',
                'KodeMerk' => 'PATEN',
                'Satuan' => 'BOTOL',
                'HargaJual' => 45000,
                'HargaPokok' => 32000,
                'Barcode' => 'APT0004',
                'Gambar' => '/images/apotek/vitamin_c.png',
                'Stock' => 120
            ],
            [
                'KodeItem' => 'APT-0005',
                'NamaItem' => 'Betadine Antiseptic Solution 30ml',
                'KodeJenisItem' => 'OBAT_BEBAS',
                'KodeMerk' => 'PATEN',
                'Satuan' => 'BOTOL',
                'HargaJual' => 28500,
                'HargaPokok' => 20000,
                'Barcode' => 'APT0005',
                'Gambar' => '/images/apotek/betadine.png',
                'Stock' => 90
            ],
            [
                'KodeItem' => 'APT-0006',
                'NamaItem' => 'Masker Medis 3-Ply Box 50s',
                'KodeJenisItem' => 'ALKES',
                'KodeMerk' => 'PATEN',
                'Satuan' => 'STRIP',
                'HargaJual' => 35000,
                'HargaPokok' => 22000,
                'Barcode' => 'APT0006',
                'Gambar' => '/images/apotek/medical_mask.png',
                'Stock' => 60
            ],
        ];

        foreach ($apotekProducts as $prod) {
            $itemmasterPayload = $filterPayload('itemmaster', [
                'KodeItem' => $prod['KodeItem'],
                'NamaItem' => $prod['NamaItem'],
                'KodeJenisItem' => $prod['KodeJenisItem'],
                'KodeMerk' => $prod['KodeMerk'],
                'TypeItem' => 1, // Inventory
                'Rak' => '-',
                'KodeGudang' => 'UMM',
                'KodeSupplier' => 'SP0001',
                'Satuan' => $prod['Satuan'],
                'Barcode' => $prod['Barcode'],
                'Gambar' => $prod['Gambar'],
                'HargaPokokPenjualan' => $prod['HargaPokok'],
                'HargaJual' => $prod['HargaJual'],
                'HargaBeliTerakhir' => $prod['HargaPokok'],
                'Stock' => $prod['Stock'],
                'StockMinimum' => 5,
                'isKonsinyasi' => 'N',
                'Active' => 'Y',
                'AcctHPP' => 5110001,
                'AcctPenjualan' => 4110001,
                'AcctPenjualanJasa' => 4110002,
                'AcctPersediaan' => 1310001,
                'VatPercent' => 0,
                'TampilkanEMenu' => 0,
                'isFlashSale' => 'N',
                'FlashSalePrice' => 0,
                'isBestSeller' => 'N',
                'RecordOwnerID' => 'demoapotek',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            DB::table('itemmaster')->updateOrInsert(
                ['KodeItem' => $prod['KodeItem'], 'RecordOwnerID' => 'demoapotek'],
                $itemmasterPayload
            );
        }

        $setupCompanyRolesAndPermissions('demoapotek', 'demoapotek@pos.dstechsmart.com', '2022');

        // ----------------------------------------------------
        // 8. CONFIGURE DEMO TIKET & GATE ACCOUNT (demogate)
        // ----------------------------------------------------
        // A. Setup Company and User
        $gateCompanyPayload = $filterPayload('company', [
            'KodePartner' => 'demogate',
            'NamaPartner' => 'Demo Wisata & Tripod Gate System',
            'NamaPIC' => 'Demo Gate',
            'AlamatTagihan' => '-',
            'NoHP' => '081234567893',
            'NoTlp' => '081234567893',
            'NIKPIC' => '-',
            'tempStore' => '',
            'icon' => '',
            'StartSubs' => Carbon::now()->toDateString(),
            'EndSubs' => Carbon::now()->addYears(10)->toDateString(),
            'ExtraDays' => 0,
            'NPWP' => '-',
            'TglPKP' => Carbon::now()->toDateString(),
            'PPN' => 0,
            'isHargaJualIncludePPN' => 0,
            'isPostingAkutansi' => 0,
            'NamaPosPrinter' => '',
            'FooterNota' => '',
            'JenisUsaha' => 'Hiburan',
            'isActive' => 1,
            'isInitialSetting' => 1,
            'MaximalUser' => 999,
            'KodePaketLangganan' => '2003',
            'Email' => 'demogate@pos.dstechsmart.com',
            'GudangPoS' => 'UMM',
            'TerminBayarPoS' => '',
            'TypeBackgraund' => 'Color',
            'Backgraund' => '#ffffff',
            'TypeKitchenBackgraund' => 'Color',
            'KitchenBackgraund' => '#ffffff',
            'QueueDesignSetting' => 'QueueManagement',
            'CustDisplayDesignSetting' => 'default',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('company')->updateOrInsert(['KodePartner' => 'demogate'], $gateCompanyPayload);

        DB::table('users')->updateOrInsert(
            ['email' => 'demogate@pos.dstechsmart.com'],
            [
                'name' => 'Demo Gate Admin',
                'password' => Hash::make('12345678'),
                'Active' => 'Y',
                'isConfirmed' => 1,
                'RecordOwnerID' => 'demogate',
                'BranchID' => '',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        // B. Setup master data
        DB::table('gruppelanggan')->updateOrInsert(
            ['KodeGrup' => '1001', 'RecordOwnerID' => 'demogate'],
            ['NamaGrup' => 'UMM', 'LevelHarga' => 1, 'DiskonPersen' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );

        DB::table('pelanggan')->updateOrInsert(
            ['KodePelanggan' => 'CUST-UMUM', 'RecordOwnerID' => 'demogate'],
            [
                'NamaPelanggan' => 'Pelanggan Umum',
                'KodeGrupPelanggan' => '1001',
                'Status' => 1,
                'ProvID' => -1,
                'KotaID' => -1,
                'KelID' => -1,
                'KecID' => -1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        DB::table('gudang')->updateOrInsert(
            ['KodeGudang' => 'UMM', 'RecordOwnerID' => 'demogate'],
            ['NamaGudang' => 'Gudang Utama Gate', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );

        DB::table('jenisitem')->updateOrInsert(
            ['KodeJenis' => 'TIKET', 'RecordOwnerID' => 'demogate'],
            ['NamaJenis' => 'Tiket Masuk', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );

        DB::table('settingaccount')->updateOrInsert(
            ['RecordOwnerID' => 'demogate'],
            [
                'InvAcctHargaPokokPenjualan' => 5110001,
                'InvAcctPendapatanJual' => 4110001,
                'InvAcctPendapatanJasa' => 4110002,
                'InvAcctPersediaan' => 1310001,
                'InvAcctPendapatanNonInventory' => 4110003,
                'InvAcctPendapatanLainLain' => 4110003,
                'InvAcctPenyesuaiaanStockMasuk' => 7111001,
                'InvAcctPenyesuaiaanStockKeluar' => 8111001,
                'PbAcctPajakPembelian' => 1130001,
                'PbAcctPembayaranTunai' => 1110001,
                'PbAcctPembayaranNonTunai' => 1120001,
                'PbAcctHutang' => 2110001,
                'PbAcctUangMukaPembelian' => 1410001,
                'PjAcctPajakPenjualan' => 2130001,
                'PjAcctPenjualanTunai' => 1110001,
                'PjAcctPenjualanNonTunai' => 1120001,
                'PjAcctPiutang' => 1140001,
                'PjAcctUangMukaPenjualan' => 4120001,
                'PjAcctGoodsInTransit' => 1310002,
                'PjAcctReturnPenjualan' => 4120001,
                'PjAcctPajakHiburan' => 2130002,
                'KnAcctHutangKonsinyasi' => 2110001,
                'KnAcctPembayaranHutang' => 1110001,
                'KnAcctPenerimaanKonsinyasi' => 2110002,
                'OthAcctModal' => 3110001,
                'OthAcctPrive' => 3110004,
                'OthAcctLabaDitahan' => 3110002,
                'OthAcctLabaTahunBerjalan' => 3110003,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        $gateTickets = [
            ['KodeItem' => 'GT-0001', 'NamaItem' => 'Tiket Masuk Terusan', 'HargaJual' => 100000, 'Gambar' => 'https://images.unsplash.com/photo-1541935661642-e1d8825832a8?w=500&auto=format&fit=crop&q=60'],
            ['KodeItem' => 'GT-0002', 'NamaItem' => 'Tiket Masuk Reguler', 'HargaJual' => 40000, 'Gambar' => 'https://images.unsplash.com/photo-1572402230267-f3e267c1e5d2?w=500&auto=format&fit=crop&q=60'],
        ];

        foreach ($gateTickets as $tkt) {
            $itemmasterPayload = $filterPayload('itemmaster', [
                'KodeItem' => $tkt['KodeItem'],
                'NamaItem' => $tkt['NamaItem'],
                'KodeJenisItem' => 'TIKET',
                'KodeMerk' => 'DSTech Ticket',
                'TypeItem' => 2, // Jasa/Tiket
                'Rak' => '-',
                'KodeGudang' => 'UMM',
                'KodeSupplier' => '-',
                'Satuan' => 'PCS',
                'Barcode' => $tkt['KodeItem'],
                'Gambar' => $tkt['Gambar'],
                'HargaPokokPenjualan' => 0,
                'HargaJual' => $tkt['HargaJual'],
                'HargaBeliTerakhir' => 0,
                'Stock' => 999999,
                'StockMinimum' => 0,
                'isKonsinyasi' => 'N',
                'Active' => 'Y',
                'VatPercent' => 0,
                'TampilkanEMenu' => 0,
                'isFlashSale' => 'N',
                'FlashSalePrice' => 0,
                'isBestSeller' => 'N',
                'RecordOwnerID' => 'demogate',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            DB::table('itemmaster')->updateOrInsert(
                ['KodeItem' => $tkt['KodeItem'], 'RecordOwnerID' => 'demogate'],
                $itemmasterPayload
            );
        }

        $setupCompanyRolesAndPermissions('demogate', 'demogate@pos.dstechsmart.com', '2003');
    }
}
