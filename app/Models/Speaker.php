<?php

namespace App\Models;
use App\Models\User;
use App\Models\TalkProposal;
use Illuminate\Database\Eloquent\SoftDeletes;
  
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'bio',
        'expertise',
        'previous_talks'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['bio', 'expertise', 'previous_talks'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function talkProposals()
    {
        return $this->hasMany(TalkProposal::class);
    }

}
