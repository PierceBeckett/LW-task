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
		Schema::create('ram', function (Blueprint $table) {
			$table->id();
			$table->string('value');
		});

		Schema::create('hdd', function (Blueprint $table) {
			$table->id();
			$table->string('value');
		});

		Schema::create('locations', function (Blueprint $table) {
			$table->id();
			$table->string('value');
		});

        Schema::create('products', function (Blueprint $table) {
            $table->id();
			$table->string('model');
			$table->integer('storage');
			$table->decimal('price', 6, 2);

			// foreign keys auto indexed in mysql
			// consider removing in place of indexes only for performance
			// and handle consistency checks in models
			$table->foreignId('ram_id')->references('id')->on('ram');
			$table->foreignId('hdd_id')->references('id')->on('hdd');
			$table->foreignId('location_id')->references('id')->on('locations');

			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ram');
        Schema::dropIfExists('hdd');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('products');
    }
};
