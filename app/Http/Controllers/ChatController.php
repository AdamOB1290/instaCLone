<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chats = Chat::with('user')->get();

        return view('chats.crud.index', compact('chats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('chats.crud.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chat = Chat::create($this->validatedData());

        event(new MessageSent($chat));

        $this->storeMediaFile($chat);;
        return redirect('chats');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        return view('chats.crud.show', compact('chat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        return view('chats.crud.update', compact('chat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Chat $chat)
    {
        $data = request()->validate([
            
        ]);

        $chat->update($data);
        return redirect('chats');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        $chat->delete();
        return redirect('chats');
    }

    protected  function validatedData()
    {

        return tap(request()->validate([
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'content' => '',
            'media_file' => '',
        ]), function () {
            if (request()->hasFile('media_file')) {
                request()->validate([
                    'media_file' => 'file|mimes:jpg,png,jpeg,gif,svg,mp4,jpeg,png,bmp,svg,avi,mkv,mpeg|max:20000 ',
                ]);
            }
        });
    }

    private function storeMediaFile($chat)
    {
        if (request()->has('media_file')) {
            $chat->update([
                'media_file' => request()->media_file->store('uploads', 'public')
            ]);
        }
    }
}
