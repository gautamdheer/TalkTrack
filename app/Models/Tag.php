<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Specify the table name if it's different from 'tags'
    protected $table = 'tags';

    // Fillable properties
    protected $fillable = [
        'name', // Assuming 'name' is a property of the tag
    ];

    // Define relationships if needed
    public function talkProposals()
    {
        return $this->belongsToMany(TalkProposal::class);
    }
}