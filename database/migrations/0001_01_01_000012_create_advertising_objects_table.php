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
        Schema::create('advertising_objects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('contract_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('advertising_type_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('region_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('city_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->foreignId('object_status_id')
                ->constrained('object_statuses')
                ->restrictOnDelete();
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertising_objects');
    }
};
