<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vendor_profiles')) {
            Schema::create('vendor_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

                // Step 1: Business Information
                $table->string('business_name')->nullable();
                $table->string('business_type')->nullable();
                $table->string('business_reg_no')->nullable();
                $table->date('establishment_date')->nullable();
                $table->text('business_address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('zip')->nullable();

                // Step 2: Contact Information
                $table->string('contact_person')->nullable();
                $table->string('designation')->nullable();
                $table->string('alt_phone')->nullable();

                // Step 3: Bank Details
                $table->string('bank_name')->nullable();
                $table->string('branch_name')->nullable();
                $table->string('account_holder_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('ifsc_code')->nullable();
                $table->string('cheque_copy')->nullable();

                // Step 4: Product Information
                $table->string('product_categories')->nullable();
                $table->string('avg_order_value')->nullable();
                $table->string('expected_listing_count')->nullable();
                $table->enum('business_model', ['manufacturer','reseller'])->nullable();
                $table->string('product_certification')->nullable();

                // Step 5: Tax & Compliance
                $table->string('pan_number')->nullable();
                $table->string('gst_number')->nullable();
                $table->string('iec_code')->nullable();
                $table->string('kyc_docs')->nullable();

                // Step 6: Logistics & Fulfillment
                $table->boolean('has_own_logistics')->default(0);
                $table->string('preferred_shipping')->nullable();
                $table->text('warehouse_address')->nullable();

                // Step 7: Terms & Agreement
                $table->boolean('agreed_terms')->default(0);

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_profiles');
    }
};
