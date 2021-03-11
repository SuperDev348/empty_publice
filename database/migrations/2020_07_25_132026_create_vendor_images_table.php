<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->unsignedInteger('vendor_id');
            $table->boolean('is_default')->default(0);
            $table->timestamps();
            $table->foreign('vendor_id')
                ->references('id')->on('vendors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_images');
    }
}
