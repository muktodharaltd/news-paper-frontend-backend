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
            ['slug' => 'details_inline_1', 'name' => 'ডিটেইল – বিবরণ ইনলাইন ১'],
            ['slug' => 'details_inline_2', 'name' => 'ডিটেইল – বিবরণ ইনলাইন ২'],
            ['slug' => 'details_inline_3', 'name' => 'ডিটেইল – বিবরণ ইনলাইন ৩'],
            ['slug' => 'details_inline_4', 'name' => 'ডিটেইল – বিবরণ ইনলাইন ৪'],
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
            'details_inline_1',
            'details_inline_2',
            'details_inline_3',
            'details_inline_4',
        ])->delete();
    }
};
