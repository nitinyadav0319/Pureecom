<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // user ka type (customer ya vendor)
            $table->enum('account_type', ['customer', 'vendor'])
                ->default('customer')
                ->after('id');

            // vendor onboarding aur approval status
            $table->enum('status', ['incomplete', 'pending', 'approved', 'rejected'])
                ->default('incomplete')
                ->after('account_type');

            // onboarding fields
            $table->string('business_name')->nullable()->after('name');
            $table->string('business_address')->nullable()->after('business_name');
            $table->string('gst_number')->nullable()->after('business_address');
            $table->string('pan_number')->nullable()->after('gst_number');
            $table->string('bank_account_number')->nullable()->after('pan_number');
            $table->string('bank_ifsc')->nullable()->after('bank_account_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_type',
                'status',
                'business_name',
                'business_address',
                'gst_number',
                'pan_number',
                'bank_account_number',
                'bank_ifsc',
            ]);
        });
    }
};
