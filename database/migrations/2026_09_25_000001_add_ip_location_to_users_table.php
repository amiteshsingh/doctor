<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * users table mein IP-based location columns add karo.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ip_city', 100)->nullable()->after('ip_address');
            $table->string('ip_region', 100)->nullable()->after('ip_city');
            $table->string('ip_country', 100)->nullable()->after('ip_region');
            $table->string('ip_isp', 150)->nullable()->after('ip_country');
            $table->decimal('ip_lat', 10, 7)->nullable()->after('ip_isp');
            $table->decimal('ip_lng', 10, 7)->nullable()->after('ip_lat');
        });
    }

    /**
     * Reverse the migrations.
     * Sab 6 location columns drop karo.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'ip_city',
                'ip_region',
                'ip_country',
                'ip_isp',
                'ip_lat',
                'ip_lng',
            ]);
        });
    }
};
