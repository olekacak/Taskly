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
        Schema::create('workspace', function (Blueprint $table) {
            $table->id('workspaceId');
            $table->string('name');
            $table->unsignedBigInteger('userId');
            $table->foreign('userId')->references('userId')->on('user')->onDelete('cascade');
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
        Schema::dropIfExists('workspace');
    }
};
