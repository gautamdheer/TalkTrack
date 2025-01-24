    <?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\TalkProposal;
    use App\Http\Requests\TalkProposalRequest;

    class TalkProposalController extends Controller
    {
            
        public function store(TalkProposalRequest $request)
        {
            $validateData = $request->validated();

            $presentation = null;
            if($request->hasFile('presentation')) {
                $validateData['presentation'] = $request->file('presentation')->store('assets/presentations', 'public');
            }

            $validateData['user_id'] = auth()->id();

            TalkProposal::create($validateData);

            if(isset($valdateData['tags'])){
                $proposal->tags()->attach($valdateData['tags']);
            }

            // Notify reviewers about the new proposal
            $reviewers = User::role(['reviewer'])->get();
            foreach($reviewers as $reviewer){
                $reviewer->notify(new ProposalSubmitted($proposal));
            }

            return response()->json(
                ['message' => 'Talk proposal created successfully.','proposal' => $proposal->load(['user', 'tags'])],
                201);

        }
        // Display a listing of the resource.
        public function index(Request $request)
        {
            $query = TalkProposal::with(['speaker','tags','reviews'])
            ->when($request->tag, function ($query, $tag) {
                return $query->whereHas('tags', function ($q) use ($tag){
                    $q->where('name', $tag);
                });
            })
            ->when($request->status, function ($query, $status){
                return $query->where('status', $status);
            })
            ->when($request->search, function($query, $search) {
                return $query->where(function ($q) use ($search){
                $q->where('title','like', "%{$search}")
                ->orwhere('description','like', "%{$search}");
            });
        });
            return response()->json($query->paginate(10));
        }

    }
