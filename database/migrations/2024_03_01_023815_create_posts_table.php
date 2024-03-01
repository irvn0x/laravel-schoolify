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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('title');
            $table->string('slug');
            $table->bigInteger('category_id')->unsigned();
            $table->text('content');
            $table->timestamps();
        });

        /*
        Crete pivot table post_tag
        Relationship Many to Many antara table post dan table tag yaitu menyimpan masing-masing ID
        dari post dan tag.
        */
        Schema::create('post_tag', function (Blueprint $table) {
            $table->integer('post_id');
            $table->integer('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};