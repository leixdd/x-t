<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Messages;
use App\Events\ChatEvent;

class MessagesController extends Controller
{

    public function index($user_id)
    {
        $sent_messages = Messages::where([
            'user_id' => Auth::user()->id,
            'sent_to' => $user_id
        ])->get()->toArray();

        $receive_messages = Messages::where([
            'sent_to' => Auth::user()->id,
            'user_id' => $user_id
        ])->get()->toArray();

        $sorted_ = collect(array_merge($sent_messages, $receive_messages))->sortBy('created_at')->values()->all();
        return $sorted_;

    }

    public function sendMessage(Request $request){
        $messages = new Messages();
        $messages->user_id = Auth::user()->id;
        $messages->message = $request->message;
        $messages->sent_to = $request->sent_to;
        $messages->save();

        broadcast(new ChatEvent($chat))->toOthers();

        return ['status' => 'success'];
    }
}
