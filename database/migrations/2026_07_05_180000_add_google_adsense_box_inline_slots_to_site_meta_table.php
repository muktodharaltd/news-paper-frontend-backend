<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_meta', function (Blueprint $table) {
            $table->string('google_adsense_box_slot', 32)->nullable()->after('google_adsense_default_slot');
            $table->string('google_adsense_inline_slot', 32)->nullable()->after('google_adsense_box_slot');
        });
    }

    public function down(): void
    {
        Schema::table('site_meta', function (Blueprint $table) {
            $table->dropColumn(['google_adsense_box_slot', 'google_adsense_inline_slot']);
        });
    }
};
