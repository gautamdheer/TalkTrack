    <?php

    use Illuminate\Http\Request;
    use App\Models\TalkProposal;

    class TalkProposalController extends Controller
    {
            
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
                $query->withAnyTags([$request->tag]);
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
