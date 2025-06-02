<?php

use App\Models\Advertisement;
use App\Models\User;
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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade'); // User who rented
            $table->foreignIdFor(Advertisement::class)->constrained()->onDelete('cascade'); // Product being rented
            $table->timestamp('rented_from'); // Start date/time of rental
            $table->timestamp('rented_until'); // End date/time of rental
            $table->timestamp('returned_at')->nullable(); // Actual date/time product was returned
            $table->unsignedTinyInteger('wear_percentage')->default(0); // 0-100
            $table->string('image_path')->nullable();
            $table->enum('status', ['active', 'returned', 'overdue', 'worn_out'])->default('active'); // Rental status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
