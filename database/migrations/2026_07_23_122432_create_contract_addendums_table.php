<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_addendums', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('number');
            $table->decimal('amount_change', 15, 2)->default(0)->comment('Изменение стоимости');
            $table->date('signed_at');
            $table->date('end_date');
            $table->text('note')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique(['contract_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_addendums');
    }
};