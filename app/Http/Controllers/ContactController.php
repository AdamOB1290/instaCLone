<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\MessageSent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function get()
    {
        $contacts = User::where('id', '!=', Auth::user()->id)->get();
        foreach ($contacts as $contact) {
            if (filter_var($contact->pfp, FILTER_VALIDATE_URL)) {

                // index it to session user
                $contact['pfp_type'] = 'imageUrl';
            } else { // else it means it's a local file

                // index it to session user
                $contact['pfp_type'] = 'localImage';
            }
        }
        

        return response()->json($contacts);
    }

    public function getMessagesFor($id)
    {
        $messages = Chat::where('sender_id', $id)->orWhere('receiver_id', $id)->get();
        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $message = Chat::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->contact_id,
            'content' => $request->text,
        ]);
        broadcast(new MessageSent($message)); 
        return response()->json($message);
    }

    
}
