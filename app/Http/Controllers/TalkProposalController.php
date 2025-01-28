<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TalkProposal;
use App\Models\Tag;
use App\Models\Speaker;
use App\Http\Controllers\Controller;

    class TalkProposalController extends Controller
    {
            
        public function create()
        {
            // pass the tags to the view
            $tags = Tag::all();
            // pass the speakers to the view
            $speakers = Speaker::all();
            return view('proposals.create', compact('tags', 'speakers'));
            
        }
        public function store(Request $request)
        {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'duration' => 'required|integer',
                'presentation_file' => 'required|file|mimes:pdf|max:10240',
                'tags' => 'required|array'
            ]);
    
            $filePath = $request->file('presentation_file')
                ->store('presentations', 'public');
    
            $proposal = TalkProposal::create([
                'speaker_id' => auth()->user()->speaker->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'duration' => $validated['duration'],
                'presentation_file' => $filePath,
                'status' => TalkProposal::STATUS_PENDING
            ]);
    
            $proposal->attachTags($validated['tags']);
    
            return response()->json([
                'message' => 'Proposal submitted successfully',
                'proposal' => $proposal
            ]);
        }
    
        public function index(Request $request)
        {
            $query = TalkProposal::query()
                ->with(['speaker.user', 'tags']);
    
            if ($request->has('tag')) {
                $query->withAnyTags([$requestcle->tag]);
            }
    
            if ($request->has('speaker')) {
                $query->whereHas('speaker.user', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->speaker}%");
                });
            }
    
            $proposals = $query->paginate(15);
    
            return response()->json($proposals);
        }

    }
