<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->string('url');
            $table->enum('media_type', ['image', 'video']);
            $table->timestamps(); 
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
