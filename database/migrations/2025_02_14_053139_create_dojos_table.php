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
        Schema::create('dojos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('description');
            $table->string('location');
        });
    }

    /**
     * Reverse the migrations.
     *        *     $table->integer('skill');
         *   $table->foreignId('dojo_id')->constrained()->onDelete('cascade');
         *             $table->string('pname');
            $table->decimal('price', 9, 3);
            $table->text('bio');
            $table->binary('image');
     */
    public function down(): void
    {
        Schema::dropIfExists('dojos');
    }
};
