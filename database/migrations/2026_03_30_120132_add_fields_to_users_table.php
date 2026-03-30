<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone_no')) {
                $table->string('phone_no', 15)->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'profile_pic')) {
                $table->string('profile_pic')->nullable()->after('phone_no');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('profile_pic');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['Male', 'Female', 'Other'])->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'dob')) {
                $table->date('dob')->nullable()->after('gender');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_no', 'profile_pic', 'address', 'gender', 'dob']);
        });
    }
};
