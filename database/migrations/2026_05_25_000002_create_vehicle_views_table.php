<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Placeholder schema — change or replace this however your approach requires.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->timestamp('viewed_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_views');
    }
};
