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
        Schema::create('advertisement_related_advertisement', function (Blueprint $table) {
            $table->foreignId('advertisement_id');
            $table->foreignId('related_advertisement_id');
            $table->primary(['advertisement_id', 'related_advertisement_id']);
            $table->timestamps();

            $table->foreign('advertisement_id', 'adv_rel_adv_adv_id_fk')
                ->references('id')->on('advertisements')->onDelete('cascade');

            $table->foreign('related_advertisement_id', 'adv_rel_adv_rel_adv_id_fk')
                ->references('id')->on('advertisements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisement_related_advertisement');
    }
};
