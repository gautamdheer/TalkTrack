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
         // Revision History Table
         Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->string('revisionable_type');
            $table->unsignedBigInteger('revisionable_id');
            $table->json('changes');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->index(['revisionable_type', 'revisionable_id']);
        });

        // Tags Table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Taggable Pivot Table
        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tag_id');
            $table->string('taggable_type');
            $table->unsignedBigInteger('taggable_id');
            $table->timestamps();

            $table->index(['taggable_type', 'taggable_id']);
        });

        // Reviews Table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reviewer_id');
            $table->unsignedBigInteger('talk_proposal_id');
            $table->text('comments');
            $table->unsignedTinyInteger('rating');
            $table->timestamps();

            $table->foreign('reviewer_id')->references('id')->on('users');
            $table->foreign('talk_proposal_id')->references('id')->on('talk_proposals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisions');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('reviews');
    }
};
