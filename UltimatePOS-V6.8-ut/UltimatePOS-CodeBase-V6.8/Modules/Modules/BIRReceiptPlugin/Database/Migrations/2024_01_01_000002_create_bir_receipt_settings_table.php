<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bir_receipt_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->nullable(); // For multi-business support
            $table->string('tin_number'); // Tax Identification Number
            $table->string('business_name');
            $table->text('business_address');
            $table->string('business_phone')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_website')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('header_text')->nullable();
            $table->text('footer_text')->nullable();
            $table->string('default_template')->default('A1');
            $table->json('custom_fields')->nullable(); // Additional custom fields
            $table->json('receipt_settings')->nullable(); // Font, size, margins, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('business_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bir_receipt_settings');
    }
};
