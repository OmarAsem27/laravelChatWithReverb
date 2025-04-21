<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('users', compact('users'));
    }


    public function chat($receiverId)
    {
        $receiver = User::find($receiverId);

        // Wrong Approach
        // $messages = Message::where('receiver_id', $user->id)->orWhere('sender_id', $receiverId)->dd();
        // "select * from `messages` where `receiver_id` = ? or `sender_id` = ?"


        $messages = Message::where(function ($query) use ($receiverId) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)->where('receiver_id', Auth::id());
        })->get();
        // "select * from `messages` where (`sender_id` = ? and `receiver_id` = ?) or (`sender_id` = ? and `receiver_id` = ?)"

        return view('chat', compact('messages', 'receiver'));
    }


    public function sendMessage(Request $request, $receiverId)
    {

        $request->validate([
            'message' => 'required|string|max:255'
        ]);

        // save the message to database
        $message = Message::create([
            'message' => $request['message'],
            'receiver_id' => $receiverId,
            'sender_id' => Auth::id(),
        ]);

        // fire the event for everyone except the Authenticated user
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'Message Sent'], 200);
    }


    public function typing(Request $request)
    {
        broadcast(new UserTyping(Auth::id()))->toOthers();

        return response()->json(['status' => 'Typing broadcasted'], 200);
    }


    public function setOnline()
    {
        Cache::put('user-is-online-' . Auth::id(), true, now()->addMinutes(5));
        return response()->json(['status' => 'Online'], 200);
    }

    
    public function setOffline()
    {
        Cache::forget('user-is-online-' . Auth::id());

        return response()->json(['status' => 'Offline'], 200);
    }




}
