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
        Schema::create('task', function (Blueprint $table) {
            $table->id('taskId');
            $table->unsignedBigInteger('workspaceId');
            $table->foreign('workspaceId')->references('workspaceId')->on('workspace')->onDelete('cascade');
            $table->text('title');
            $table->text('description')->nullable();
            $table->timestamp('deadline');
            $table->boolean('status')->nullable();
            $table->boolean('is_active');
            $table->boolean('is_delete');
            $table->timestamp('created_date');
            $table->timestamp('modified_date');
            $table->timestamp('deleted_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
