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
        Schema::create('sets_have_armors', function (Blueprint $table) {
            $table->foreignId('id_sets')->references('id')->on('sets')->onDelete('cascade');
            $table->foreignId('id_armors')->references('id')->on('armors')->onDelete('cascade');

            $table->primary(['id_sets', 'id_armors']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sets_have_armors');
    }
};
