<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('book_id')->index();
            $table->tinyInteger('sorting')->default(0)->index();

            $table->timestamps();
        });
    }
};
