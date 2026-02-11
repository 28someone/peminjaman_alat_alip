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
        Schema::create('tool_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('requested_return_date')->nullable();
            $table->date('received_date')->nullable();
            $table->enum('status', ['pending', 'verified'])->default('pending');
            $table->string('condition_after_return')->nullable();
            $table->decimal('fine', 12, 2)->default(0);
            $table->text('note')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_returns');
    }
};
