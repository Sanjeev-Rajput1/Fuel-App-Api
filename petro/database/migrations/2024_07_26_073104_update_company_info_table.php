<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('company_info', function (Blueprint $table) {
            // Rename the 'images' column to 'upload_bir'
            $table->renameColumn('images', 'upload_bir');

            // Add the new 'upload_sec' column
            $table->string('upload_sec')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_info', function (Blueprint $table) {
            // Rename the 'upload_bir' column back to 'images'
            $table->renameColumn('upload_bir', 'images');

            // Drop the 'upload_sec' column
            $table->dropColumn('upload_sec');
        });
    }
	
};
