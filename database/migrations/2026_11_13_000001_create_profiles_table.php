<?php

declare(strict_types=1);

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
        Schema::create('profiles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('headline');
            $table->string('tagline');
            $table->text('bio')->nullable();
            $table->string('location');
            $table->boolean('is_available')->default(true);
            $table->string('email')->nullable();
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('resume_url')->nullable();
            $table->string('resume_path')->nullable();
            $table->string('avatar_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
