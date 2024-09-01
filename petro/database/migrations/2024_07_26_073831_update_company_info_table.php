<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('company_info', function (Blueprint $table) {
            // Modify columns
            $table->after('upload_bir', function ($table) {
                $table->string('upload_sec')->change(); // Adjust datatype as needed
            });

        });
    }

    public function down()
    {
        Schema::table('company_info', function (Blueprint $table) {
            // Revert column changes if necessary
            $table->integer('upload_sec')->change(); // Revert datatype as needed
         
        });
    }
};
