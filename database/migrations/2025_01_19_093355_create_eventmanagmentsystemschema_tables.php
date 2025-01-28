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
        
        // speakers Table
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->string('expertise');
            $table->json('previous_talks')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('social_media_links')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

         // Talk Proposal Table
         Schema::create('talk_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speaker_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('duration')->comment('Duration in minutes');
            $table->string('presentation_file')->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])
                  ->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->json('technical_requirements')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();
            $table->softDeletes();
        });

        // Reviews Table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('talk_proposal_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->comment('Rating from 1 to 5');
            $table->text('comments');
            $table->enum('recommendation', ['accept', 'reject', 'revise'])->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->unique(['reviewer_id', 'talk_proposal_id']);
        });

        // Talk Proposal Revisions Table
        Schema::create('talk_proposal_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talk_proposal_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('changes');
            $table->text('revision_notes')->nullable();
            $table->timestamps();
        });

        // Reviewer Assignments Table
        Schema::create('reviewer_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('talk_proposal_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'talk_proposal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speakers');
        Schema::dropIfExists('talk_proposals');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('talk_proposal_revisions');
        Schema::dropIfExists('reviewer_assignments');


    }
};
