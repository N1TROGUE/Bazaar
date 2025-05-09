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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class);
            $table->foreignIdFor(\App\Models\AdvertisementCategory::class)->nullable();
            $table->foreignIdFor(\App\Models\AdvertisementImage::class);

            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('ad_type', ['sale', 'rental']);
            $table->boolean('allow_bids')->default(false);
            $table->integer('rental_min_duration_hours')->nullable();
            $table->integer('rental_max_duration_hours')->nullable();
            $table->enum('status', ['draft', 'active', 'inactive', 'sold', 'rented_out', 'expired', 'pending_approval'])->default('draft')->index();
            $table->dateTime('expiration_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
