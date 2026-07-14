<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_profiles', 'step_completed')) {
                $table->integer('step_completed')->default(0)->after('agreed_terms');
            }
        });
    }

    public function down()
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_profiles', 'step_completed')) {
                $table->dropColumn('step_completed');
            }
        });
    }
};
