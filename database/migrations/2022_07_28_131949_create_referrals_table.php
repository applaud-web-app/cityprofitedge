<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('referrals', function (Blueprint $table) {
        //     $table->id();

        //     $table->integer('level')->default(0)->nullable(false);
        //     $table->decimal('percent', 5, 2)->default(0)->nullable(false);

        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('referrals');
    }
};
