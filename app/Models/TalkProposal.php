<?php

namespace App\Models;

use App\Models\Speaker;
use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

class TalkProposal extends Model
{
    use SoftDeletes, LogsActivity, HasTags;

    protected $fillable = [
        'speaker_id',
        'title',
        'description',
        'presentation_file',
        'status',
        'duration'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public function speaker()
    {
        return $this->belongsTo(Speaker::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
