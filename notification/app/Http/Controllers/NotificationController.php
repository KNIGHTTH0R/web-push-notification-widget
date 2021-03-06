<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Notification;
use App\SentNotification;
use App\Subscriber;
use Illuminate\Http\Request;
use Artisan;
use App\Http\Requests;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->get();
        return response()->json($notifications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNotificationRequest $request)
    {
        $user = Auth::user();
        // $segment = $user->segments->first();
        $notification = $user->notifications()->create($request->all());
        
        // $notification->segment = $segment->toArray();
        // $notification->save();

        $exitCode = Artisan::call('send:notification', [
            'notification' => $notification->id,
        ]);
        
        return redirect()->action('DashboardController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($did)
    {
        $notification = Subscriber::where("did", "=", $did)->first()->user->notifications->last();
        return response()->json($notification);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getLatestNotification($user_id)
    {
        $notification = Notification::where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
        return response()->json($notification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSentNotification(Request $request)
    {
        $sentNotification = SentNotification::where('notification_id', '=', $request->notification_id)->get()->last();
        if($request->delivered) {
            $sentNotification->increment('delivered');
        } 

        if ($request->clicked) {
            $sentNotification->increment('clicked');  
        }
        
        return $sentNotification;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
