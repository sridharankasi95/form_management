<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('label');
            $table->enum('type', [
                'text', 'number', 'email',
                'date', 'dropdown', 'checkbox'
            ]);
            $table->boolean('required')->default(false);
            $table->json('validation_rules')->nullable();
            // For dropdown options: ["Option1","Option2"]
            $table->json('options')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
