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
        Schema::table('tblproductdata', function (Blueprint $table) {
            $table->unsignedInteger('stock')->nullable()->after('strProductCode');
            $table->decimal('price', 10, 2)->nullable()->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblproductdata', function (Blueprint $table) {
            $table->dropColumn('stock');
            $table->dropColumn('price');
        });
    }
};
