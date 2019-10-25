<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSermonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sermons', function (Blueprint $table) {
            $table->string('id', 150)->primary();
            $table->string('ministry_id', 150)->index();
            $table->string('title');
            $table->string('preacher');
            $table->date('date')->default(today());
            $table->json('main_verses');
            $table->json('reference_verses')->nullable();
            $table->longText('sermon');
            $table->string('resource')->nullable();
            $table->timestamps();

            $table->foreign('ministry_id')->references('id')->on('ministries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sermons');
    }
}
