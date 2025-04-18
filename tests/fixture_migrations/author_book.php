<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('author_book', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('book_id');
            $table->string('role')->nullable();
            $table->tinyInteger('sorting')->default(0)->index();

            $table->index(['author_id', 'book_id']);
        });
    }
};
