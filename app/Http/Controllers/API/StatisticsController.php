<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TalkProposal;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_proposals' => TalkProposal::count(),
            'average_rating' => Review::avg('rating'),
            'proposals_by_tag' => TalkProposal::withCount('tags')
                ->get()
                ->groupBy('tags_count'),
            'status_distribution' => TalkProposal::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
        ];

        return response()->json($stats);
    }
}
