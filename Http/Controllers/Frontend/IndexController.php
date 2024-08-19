<?php

namespace Modules\CHJumpSeat\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Models\User;
use App\Services\FinanceService;
use App\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;
use Modules\CHJumpSeat\Models\CHJumpseatRequest;
use Modules\CHJumpSeat\Notifications\JumpSeatRequested;

/**
 * Class $CLASS$
 * @package
 */
class IndexController extends Controller
{
    public function __construct(public FinanceService $financeService)
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $jsrs = CHJumpseatRequest::where(['user_id' => Auth::user()->id])->orderByDesc('created_at')->paginate(20);
        return view('chjumpseat::index', ['requests' => $jsrs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function create(Request $request)
    {
        $price = new Money(setting('ch_jumpseat.price', 100000));
        return view('chjumpseat::create', ['price' => $price]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if (!isset($data['airport_id'])) {
            Flash::error("No Airport Selected! Please select an airport before submitting a request.");
            return response()->redirectToRoute('chjumpseat.create');
        }

        // If pay for immediate Jumpseat, check the balance and apply to journal, then move the pilot.
        $user = User::find(Auth::user()->id);
        $data['user_id'] = Auth::user()->id;
        $curr_apt = $user->curr_airport_id;

        if ($curr_apt === $data['airport_id']) {
            Flash::info("Jumpseat Request Cancelled. You're already at this airport!");
            return response()->redirectToRoute('frontend.dashboard.index');
        }
        $jumpseat_price = new Money(setting('ch_jumpseat.price', 100000));
        $journal = optional($user->journal)->balance ?? new Money(0);
        // Create a new jumpseat Request
        $jsr = CHJumpseatRequest::create($data);
        if ($jsr->type == 1 && $journal->getValue() >= $jumpseat_price->getValue()) {

            $user->curr_airport_id = $data['airport_id'];
            $user->save();
            $jsr->status = 1;
            $jsr->save();
            $this->financeService->debitFromJournal($user->journal, $jumpseat_price, $jsr, "Jumpseat: {$curr_apt}->{$data['airport_id']}", null, null);
            Flash::success("Jumpseat Request Fulfilled");
            return response()->redirectToRoute('frontend.dashboard.index');
        } else {
            $jsr->type = 0;
            $jsr->save();
            Notification::send($jsr, new JumpSeatRequested($jsr));
            $add = "";
            if (!($journal->getValue() >= $jumpseat_price->getValue())) {
                Flash::warning("Jumpseat request created. However, you did not have sufficient funds to make a instant transfer. Your Request has been submitted for approval.");
            } else {
                Flash::info("Jumpseat request created.");
            }
        }

        return response()->redirectToRoute('chjumpseat.index');
    }
}
