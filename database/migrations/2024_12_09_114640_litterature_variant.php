<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('litterature_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('litterature_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('language');
            $table->string('title');
            $table->string('description');
            $table->string('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('litterature_variants');
    }
};
