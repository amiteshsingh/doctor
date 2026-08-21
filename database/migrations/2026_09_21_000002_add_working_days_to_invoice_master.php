<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkingDaysToInvoiceMaster extends Migration
{
    public function up()
    {
        Schema::table('invoice_master', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_master', 'working_days')) {
                $table->text('working_days')->nullable()->after('max_bookings');
            }
        });
    }

    public function down()
    {
        Schema::table('invoice_master', function (Blueprint $table) {
            $table->dropColumn('working_days');
        });
    }
}
