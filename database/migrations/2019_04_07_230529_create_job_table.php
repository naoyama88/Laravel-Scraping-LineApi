<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job', function (Blueprint $table) {
            $table->string('id', 8);
            $table->string('category', 100);
            $table->string('title', 200);
            $table->string('href', 100);
            $table->string('sent_01', 1);
            $table->string('sent_01', 1);
            $table->string('sent_01', 1);
            $table->timestamp('post_datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job');
    }
}
