<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_meta', function (Blueprint $table) {
            if (! Schema::hasColumn('site_meta', 'photocard_title_scale')) {
                $table->unsignedTinyInteger('photocard_title_scale')->default(100)->after('primary_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_meta', function (Blueprint $table) {
            if (Schema::hasColumn('site_meta', 'photocard_title_scale')) {
                $table->dropColumn('photocard_title_scale');
            }
        });
    }
};
