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
        Schema::create('bir_receipt_customizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->string('template_code'); // A1, A2, etc.
            $table->string('customization_name');
            $table->text('description')->nullable();
            $table->json('layout_settings')->nullable(); // Custom layout modifications
            $table->json('field_settings')->nullable(); // Custom field configurations
            $table->json('style_settings')->nullable(); // Custom styling
            $table->text('custom_css')->nullable(); // Additional CSS
            $table->text('custom_js')->nullable(); // Additional JavaScript
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            $table->index(['business_id', 'template_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bir_receipt_customizations');
    }
};
