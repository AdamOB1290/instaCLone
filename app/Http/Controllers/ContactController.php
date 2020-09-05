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

        $unreadIds = Chat::select(\DB::raw('`sender_id` as sender_id, count(`sender_id`) as messages_count'))
            ->where('receiver_id', Auth::user()->id)
            ->where('read', false)
            ->groupBy('sender_id')
            ->get();
        
        $contacts = $contacts->map(function($contact) use ($unreadIds) {
            $contactUnread = $unreadIds->where('sender_id', $contact->id)->first();

            $contact->unread = $contactUnread ? $contactUnread->messages_count: 0;
            return $contact;
        });

        return response()->json($contacts);
    }

    public function getMessagesFor($id)
    {
        // mark all messages with the selected contact as read
        Chat::where('sender_id', $id)->where('receiver_id', Auth::user()->id)->update(['read' =>true]);

        // get all the messages that:
        $messages = Chat::where(function($querry) use ($id) {
            // either  the auth user sent
            $querry->where('sender_id', Auth::user()->id);
            $querry->where('receiver_id', $id);
        })->orWhere(function($querry) use($id) {
            // or  the auth user received
            $querry->where('sender_id', $id);
            $querry->where('receiver_id',Auth::user()->id);
        })->get();
        
        
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
