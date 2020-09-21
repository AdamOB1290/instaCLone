<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\MessageSent;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function get()
    {
        $followedUsers = Auth::user()->followed;
        
        $contacts = User::findMany($followedUsers);
        // foreach ($contacts as $contact) {
        //     if (filter_var($contact->pfp, FILTER_VALIDATE_URL)) {

        //         // index it to session user
        //         $contact['pfp_type'] = 'imageUrl';
        //     } else { // else it means it's a local file

        //         // index it to session user
        //         $contact['pfp_type'] = 'localImage';
        //     }
        // }

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

        foreach ($messages as $message) {

            if ($message->shared_post != null) {

                $sharedPost = $message->shared_post;
                $user = User::findOrFail($sharedPost['user_id']);

                // if (filter_var($user->pfp, FILTER_VALIDATE_URL)) {
                //     // index it to session user
                //     $user['pfp_type'] = 'imageUrl';
                // } else { // else it means it's a local file

                //     // index it to session user
                //     $user['pfp_type'] = 'localImage';
                // }

                $sharedPost['user']=$user;


                // if (filter_var($sharedPost['media_file'], FILTER_VALIDATE_URL)) {

                //     // index it to post
                //     $sharedPost['media_type'] = 'imageUrl';

                // } else { // else it means it's a local file

                //     // check if the local file is an image
                //     if (strstr(mime_content_type('storage/' . $sharedPost->media_file), "image/")) {

                //         // index it to post
                //         $sharedPost['media_type'] = 'localImage';

                //         // else check if the local file is a video
                //     } elseif (strstr(mime_content_type('storage/' . $sharedPost->media_file), "video/")) {

                //         // index it to post
                //         $sharedPost['media_type'] = 'localVideo';
                //     }
                // }
                
                $message->shared_post= $sharedPost; 
            }
        }

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $sharedPost= '';
        
        if ($request->sharedPostId != null) {
            $sharedPost= Post::findOrFail($request->sharedPostId);
            $user = User::findOrFail($sharedPost['user_id']);

            // if (filter_var($user->pfp, FILTER_VALIDATE_URL)) {
            //     // index it to session user
            //     $user['pfp_type'] = 'imageUrl';
            // } else { // else it means it's a local file

            //     // index it to session user
            //     $user['pfp_type'] = 'localImage';
            // }
            $sharedPost['user']=$user;

            // if (filter_var($sharedPost['media_file'], FILTER_VALIDATE_URL)) {

            //     // index it to post
            //     $sharedPost['media_type'] = 'imageUrl';

            // } else { // else it means it's a local file

            //     // check if the local file is an image
            //     if (strstr(mime_content_type('storage/' . $sharedPost->media_file), "image/")) {

            //         // index it to post
            //         $sharedPost['media_type'] = 'localImage';

            //         // else check if the local file is a video
            //     } elseif (strstr(mime_content_type('storage/' . $sharedPost->media_file), "video/")) {

            //         // index it to post
            //         $sharedPost['media_type'] = 'localVideo';
            //     }
            // }

        }


        $message = Chat::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->contactId,
            'content' => $request->text,
            'shared_post' => $sharedPost,
        ]);

        if ($message->sender_id != $message->receiver_id) {
            broadcast(new MessageSent($message)); 
        }

        return response()->json($message);
    }

    
}
