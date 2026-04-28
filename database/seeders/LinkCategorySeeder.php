<?php

namespace Database\Seeders;

use App\Models\LinkCategory;
use App\Models\QuickLink;
use Illuminate\Database\Seeder;

class LinkCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'ছাত্রছাত্রীদের তথ্য',
                'image' => 'menu01.jpg',
                'order' => 1,
                'links' => [
                    ['title' => 'ছাত্রছাত্রীর আসন সংখ্যা', 'url' => '#'],
                    ['title' => 'ভর্তি তথ্য', 'url' => '#'],
                    ['title' => 'নোটিশ', 'url' => '#'],
                    ['title' => 'রুটিন', 'url' => '#'],
                    ['title' => 'কৃতি শিক্ষার্থী', 'url' => '#'],
                ]
            ],
            [
                'title' => 'শিক্ষকদের তথ্য',
                'image' => 'menu01.jpg',
                'order' => 2,
                'links' => [
                    ['title' => 'শিক্ষকবৃন্দ', 'url' => '#'],
                    ['title' => 'শূণ্যপদের তালিকা', 'url' => '#'],
                    ['title' => 'প্রাক্তন প্রধান শিক্ষক', 'url' => '#'],
                    ['title' => 'কর্মকর্তা কর্মচারী', 'url' => '#'],
                    ['title' => 'পরিচালনা পরিষদ', 'url' => '#'],
                ]
            ],
            [
                'title' => 'ডাউনলোড',
                'image' => 'menu03.jpg',
                'order' => 3,
                'links' => [
                    ['title' => '১ম সাময়িকি পরীক্ষার রুটিন ডাউনলোড', 'url' => '#'],
                    ['title' => 'এসএসসি পরীক্ষার রুটিন ডাউনলোড', 'url' => '#'],
                    ['title' => 'ছুটির নোটিশ ডাউনলোড', 'url' => '#'],
                    ['title' => 'ভর্তি ফরম ডাউনলোড', 'url' => '#'],
                    ['title' => 'পরীক্ষার রুটিন ডাউনলোড', 'url' => '#'],
                ]
            ],
            [
                'title' => 'একাডেমীক তথ্য',
                'image' => 'menu04.jpg',
                'order' => 4,
                'links' => [
                    ['title' => 'কক্ষ সংখ্যা', 'url' => '#'],
                    ['title' => 'ছাত্রছাত্রীর আসন সংখ্যা', 'url' => '#'],
                    ['title' => 'ছুটির তালিকা', 'url' => '#'],
                    ['title' => 'মাল্টিমিডিয়া ক্লাসরুম', 'url' => '#'],
                    ['title' => 'যানবাহন সুবিধা', 'url' => '#'],
                ]
            ],
        ];

        foreach ($data as $catData) {
            // Create Category
            $category = LinkCategory::create([
                'title' => $catData['title'],
                'image' => $catData['image'],
                'order' => $catData['order'],
                'is_active' => true,
            ]);

            // Create related links
            foreach ($catData['links'] as $index => $linkData) {
                QuickLink::create([
                    'link_category_id' => $category->id,
                    'title' => $linkData['title'],
                    'url'   => $linkData['url'],
                    'order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
