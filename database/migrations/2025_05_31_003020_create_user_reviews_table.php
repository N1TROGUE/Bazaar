<?php

use App\Models\Order;
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
        Schema::create('user_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'reviewer_id')->comment('User who wrote the review (buyer)');
            $table->foreignIdFor(User::class, 'seller_id')->comment('User being reviewed (seller)');
            $table->foreignIdFor(Order::class)->unique(); // Ensure one review per order
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reviews');
    }
};
