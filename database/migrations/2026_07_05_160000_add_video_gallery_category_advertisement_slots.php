<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('advertisements')) {
            return;
        }

        $slots = [
            ['slug' => 'video_details_below_menu', 'name' => 'ভিডিও ডিটেইল – মেনুর নিচে'],
            ['slug' => 'video_details_right_1', 'name' => 'ভিডিও ডিটেইল – ডান কলাম ১'],
            ['slug' => 'video_details_right_2', 'name' => 'ভিডিও ডিটেইল – ডান কলাম ২'],
            ['slug' => 'video_details_inline_1', 'name' => 'ভিডিও ডিটেইল – বিবরণ ইনলাইন ১'],
            ['slug' => 'video_details_inline_2', 'name' => 'ভিডিও ডিটেইল – বিবরণ ইনলাইন ২'],
            ['slug' => 'video_details_inline_3', 'name' => 'ভিডিও ডিটেইল – বিবরণ ইনলাইন ৩'],
            ['slug' => 'video_details_inline_4', 'name' => 'ভিডিও ডিটেইল – বিবরণ ইনলাইন ৪'],
            ['slug' => 'gallery_details_below_menu', 'name' => 'গ্যালারি ডিটেইল – মেনুর নিচে'],
            ['slug' => 'gallery_details_right_1', 'name' => 'গ্যালারি ডিটেইল – ডান কলাম ১'],
            ['slug' => 'gallery_details_right_2', 'name' => 'গ্যালারি ডিটেইল – ডান কলাম ২'],
            ['slug' => 'gallery_details_inline_1', 'name' => 'গ্যালারি ডিটেইল – বিবরণ ইনলাইন ১'],
            ['slug' => 'gallery_details_inline_2', 'name' => 'গ্যালারি ডিটেইল – বিবরণ ইনলাইন ২'],
            ['slug' => 'gallery_details_inline_3', 'name' => 'গ্যালারি ডিটেইল – বিবরণ ইনলাইন ৩'],
            ['slug' => 'gallery_details_inline_4', 'name' => 'গ্যালারি ডিটেইল – বিবরণ ইনলাইন ৪'],
            ['slug' => 'gallery_category_below_menu', 'name' => 'গ্যালারি ক্যাটাগরি – মেনুর নিচে'],
            ['slug' => 'gallery_category_right_1', 'name' => 'গ্যালারি ক্যাটাগরি – ডান কলাম ১'],
            ['slug' => 'gallery_category_right_2', 'name' => 'গ্যালারি ক্যাটাগরি – ডান কলাম ২'],
            ['slug' => 'video_category_below_menu', 'name' => 'ভিডিও ক্যাটাগরি – মেনুর নিচে'],
            ['slug' => 'video_category_right_1', 'name' => 'ভিডিও ক্যাটাগরি – ডান কলাম ১'],
            ['slug' => 'video_category_right_2', 'name' => 'ভিডিও ক্যাটাগরি – ডান কলাম ২'],
        ];

        foreach ($slots as $slot) {
            if (DB::table('advertisements')->where('slug', $slot['slug'])->exists()) {
                continue;
            }

            $row = [
                'slug' => $slot['slug'],
                'name' => $slot['name'],
                'image' => null,
                'link' => null,
                'caption' => null,
                'video_youtube_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('advertisements', 'image_mobile')) {
                $row['image_mobile'] = null;
            }

            DB::table('advertisements')->insert($row);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('advertisements')) {
            return;
        }

        DB::table('advertisements')->whereIn('slug', [
            'video_details_below_menu',
            'video_details_right_1',
            'video_details_right_2',
            'video_details_inline_1',
            'video_details_inline_2',
            'video_details_inline_3',
            'video_details_inline_4',
            'gallery_details_below_menu',
            'gallery_details_right_1',
            'gallery_details_right_2',
            'gallery_details_inline_1',
            'gallery_details_inline_2',
            'gallery_details_inline_3',
            'gallery_details_inline_4',
            'gallery_category_below_menu',
            'gallery_category_right_1',
            'gallery_category_right_2',
            'video_category_below_menu',
            'video_category_right_1',
            'video_category_right_2',
        ])->delete();
    }
};
