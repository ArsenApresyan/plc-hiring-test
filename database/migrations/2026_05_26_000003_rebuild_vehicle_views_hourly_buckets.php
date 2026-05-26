<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('vehicle_views');

        Schema::create('vehicle_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->timestamp('bucket_hour');
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();

            $table->unique(['vehicle_id', 'bucket_hour']);
            $table->index('bucket_hour');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_views');

        Schema::create('vehicle_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->timestamp('viewed_at')->useCurrent()->index();
        });
    }
};
