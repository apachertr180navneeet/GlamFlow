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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->json('services')->nullable();
            $table->foreignId('duration_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('qty')->default(1);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('advance', 10, 2)->default(0);
            $table->decimal('remaining', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
