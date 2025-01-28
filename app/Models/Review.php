<?php

namespace App\Models;
use App\Models\User;
use App\Models\TalkProposal;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $fillable = [
        'reviewer_id',
        'talk_proposal_id',
        'rating',
        'comments',
        'status'
    ];

    protected $casts = [
        'rating' => 'integer'
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function talkProposal()
    {
        return $this->belongsTo(TalkProposal::class);
    }

}
