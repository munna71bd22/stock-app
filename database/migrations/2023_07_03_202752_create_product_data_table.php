<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblProductData', function (Blueprint $table) {
            $table->id('intProductDataId');
            $table->string('strProductCode')->unique();
            $table->string('strProductName');
            $table->string('strProductDesc');
            $table->datetime('dtmAdded')->nullable();
            $table->datetime('dtmDiscontinued')->nullable();
            $table->timestamp('stmTimestamp')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblProductData');
    }
}
