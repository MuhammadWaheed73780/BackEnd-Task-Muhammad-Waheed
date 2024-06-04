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
        //
        Schema::table("Product", function (Blueprint $table){
            $table->foreign("CategoryID")->references("id")->on("Category");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table("Product", function (Blueprint $table){
            $table->dropforeign(["CategoryID"]);
        });
    }
};
