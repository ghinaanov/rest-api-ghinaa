<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan data produk
        Product::create([
            'name' => 'Bucket Bunga Mawar Putih',
            'price' => 50000,
            'description' => 'Sangat cantik dan cocok untuk hadiah.',
        ]);

        Product::create([
            'name' => 'Bucket Bunga Tulip Merah',
            'price' => 70000,
            'description' => 'Sangat cantik dan cocok untuk hadiah.',
        ]);

        // Memperbarui file JSON
        $products = Product::all(); // Ambil semua data produk dari database
        $jsonData = $products->toJson(JSON_PRETTY_PRINT); // Konversi ke JSON dengan format rapi

        // Pastikan folder penyimpanan JSON tersedia
        Storage::makeDirectory('public/products');

        // Simpan data JSON ke file
        Storage::put('public/products/products.json', $jsonData);

        $this->command->info('File JSON berhasil diperbarui!');
    }
}
