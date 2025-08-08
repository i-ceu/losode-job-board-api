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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('title');
            $table->string('company_name');
            $table->string('location');
            $table->enum('employment_type', ['full-time', 'part-time', 'contract', 'freelance', 'internship']);
            $table->string('salary_range');
            $table->string('benefits');
            $table->string('work_condition');
            $table->text('description');
            $table->date('submission_deadline');
            $table->string('category');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_post');
    }
};
