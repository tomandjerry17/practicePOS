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
        Schema::create('bir_receipt_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_code')->unique(); // A1, A2, B1, etc.
            $table->string('template_name');
            $table->text('description')->nullable();
            $table->text('template_content'); // HTML/Blade template content
            $table->json('template_settings')->nullable(); // JSON settings for the template
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bir_receipt_templates');
    }
};
