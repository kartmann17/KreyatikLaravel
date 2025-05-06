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
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('monthly_price');
            $table->string('annual_price');
            $table->text('features')->nullable();
            $table->boolean('is_highlighted')->default(false);
            $table->string('highlight_text')->nullable();
            $table->string('button_text')->default("S\'abonner");
            $table->string('starting_text')->default('À partir de');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_custom_plan')->default(false);
            $table->string('custom_plan_text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
