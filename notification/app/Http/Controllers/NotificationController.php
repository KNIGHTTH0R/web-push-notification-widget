<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Notification;
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
        $userId = Auth::user()->id;
        $notification = User::find($userId)->notifications()->create($request->all());

        $exitCode = Artisan::call('send:notification', [
            'notification' => $notification->id,
        ]);
        
        return redirect()->action('DashboardController@index');

        // return response()->json($notification);
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
