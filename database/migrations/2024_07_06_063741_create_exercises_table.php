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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->longText('content');
            $table->text('hint1')->nullable();
            $table->text('hint2')->nullable();
            $table->text('hint3')->nullable();
            $table->enum('difficulty', ['Easy', 'Normal', 'Hard']);
            $table->boolean('allow_automatic_check_view')->default(false);
            $table->boolean('allow_automatic_check_run')->default(false);
            $table->unsignedInteger('time_number');
            $table->string('time_unit');
            $table->unsignedInteger('submission_count')->default(0);
            $table->longText('code_solution')->nullable();
            $table->text('check1')->nullable();
            $table->text('check1_answer')->nullable();
            $table->text('check2')->nullable();
            $table->text('check2_answer')->nullable();
            $table->text('check3')->nullable();
            $table->text('check3_answer')->nullable();
            $table->enum('exercise_visibility', ['public', 'private'])->default('public');
            $table->string('exercise_password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
