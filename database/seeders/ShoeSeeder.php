<?php

namespace Database\Seeders;

use App\Models\Shoe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ShoeSeeder extends Seeder
{
    public function run(): void
    {
        $shoes = [
            [
                'name' => 'Nike Air Max 270',
                'category' => 'Running',
                'price' => 149.99,
                'deleted_price' => 179.99,
                'best_seller' => true,
                'image' => 'nike-air-max-270.jpg',
                'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'Adidas Ultraboost 22',
                'category' => 'Running',
                'price' => 189.99,
                'deleted_price' => null,
                'best_seller' => true,
                'image' => 'adidas-ultraboost.jpg',
                'image_url' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'Nike Jordan 1 Retro High',
                'category' => 'Basketball',
                'price' => 169.99,
                'deleted_price' => 199.99,
                'best_seller' => true,
                'image' => 'nike-jordan-1.jpg',
                'image_url' => 'https://images.unsplash.com/photo-1597045566677-8cf032ed6634?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'Converse Chuck Taylor All Star',
                'category' => 'Casual',
                'price' => 59.99,
                'deleted_price' => null,
                'best_seller' => true,
                'image' => 'converse-chuck-taylor.jpg',
                'image_url' => 'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'New Balance 574',
                'category' => 'Casual',
                'price' => 84.99,
                'deleted_price' => 99.99,
                'best_seller' => false,
                'image' => 'new-balance-574.jpg',
                'image_url' => 'https://images.unsplash.com/photo-1539185441755-769473a23570?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'Puma RS-X3',
                'category' => 'Casual',
                'price' => 109.99,
                'deleted_price' => null,
                'best_seller' => false,
                'image' => 'puma-rsx3.jpg',
                'image_url' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'Nike Dunk Low',
                'category' => 'Skateboarding',
                'price' => 119.99,
                'deleted_price' => 139.99,
                'best_seller' => false,
                'image' => 'nike-dunk-low.jpg',
                'image_url' => 'https://images.unsplash.com/photo-1612902376491-7a8a99b60d47?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'Vans Old Skool',
                'category' => 'Skateboarding',
                'price' => 69.99,
                'deleted_price' => null,
                'best_seller' => false,
                'image' => 'vans-old-skool.jpg',
                'image_url' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'Reebok Classic Leather',
                'category' => 'Casual',
                'price' => 79.99,
                'deleted_price' => 89.99,
                'best_seller' => false,
                'image' => 'reebok-classic.jpg',
                'image_url' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'Asics Gel-Kayano 28',
                'category' => 'Running',
                'price' => 159.99,
                'deleted_price' => null,
                'best_seller' => false,
                'image' => 'asics-gel-kayano.jpg',
                'image_url' => 'https://images.unsplash.com/photo-1584735175315-9d5df23860e6?w=400&h=400&fit=crop',
            ],
        ];

        $storagePath = public_path('shoes');

        foreach ($shoes as $shoe) {
            $imageUrl = $shoe['image_url'];
            $imageName = $shoe['image'];
            $imagePath = $storagePath . '/' . $imageName;

            try {
                $response = Http::get($imageUrl);
                if ($response->successful()) {
                    File::put($imagePath, $response->body());
                }
            } catch (\Exception $e) {
                $this->command->warn("Failed to download: {$imageUrl}");
            }

            unset($shoe['image_url']);
            Shoe::create($shoe);
        }
    }
}
