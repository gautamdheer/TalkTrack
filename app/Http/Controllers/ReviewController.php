<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TalkProposal;
use App\Models\Review;
use App\Notifications\ProposalReviewed;

class ReviewController extends Controller
{
    
    public function store(Request $request, TalkProposal $proposal)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comments' => 'required|string'
        ]);

        $review = Review::create([
            'reviewer_id' => auth()->id(),
            'talk_proposal_id' => $proposal->id,
            'rating' => $validated['rating'],
            'comments' => $validated['comments']
        ]);

        $proposal->speaker->user->notify(new ProposalReviewed($proposal));

        return response()->json([
            'message' => 'Review submitted successfully',
            'review' => $review
        ]);
    }
    
}
