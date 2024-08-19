<?php

namespace Modules\CHJumpSeat\Http\Controllers\Admin;

use App\Contracts\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Modules\CHJumpSeat\Models\CHJumpseatRequest;
use Modules\CHJumpSeat\Notifications\JumpSeatApproval;
use Modules\CHJumpSeat\Notifications\JumpSeatRequested;

/**
 * Admin controller
 */
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {

        return view('chjumpseat::admin.index', ['requests' => CHJumpseatRequest::orderByDesc('created_at')->paginate(30)]);
    }

    public function status(CHJumpseatRequest $id, Request $request) {
        $status = $request->input('status');

        $id->status = $status;
        $id->approver_id = Auth::user()->id;
        $id->save();

        if ($request->input('status') == 1) {
            // do the Jumpseat
            $user = User::find($id->user_id);

            $user->curr_airport_id = $id->airport_id;
            $user->save();
            Notification::send($id, new JumpSeatApproval($id));
        }
        return response()->redirectToRoute('admin.chjumpseat.index');
    }
}
