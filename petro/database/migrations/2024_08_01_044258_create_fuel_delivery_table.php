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
        Schema::create('fuel_delivery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('fuel_type');
            $table->decimal('quantity', 8, 2); // Assuming quantity is a decimal value
            $table->string('location');
            $table->string('preferred_time');
            $table->string('status'); // Consider using an enum or a set of predefined statuses
            $table->timestamps();
        });
    }

  
    
    public function down(): void
    {
        Schema::dropIfExists('fuel_delivery');
    }
};
