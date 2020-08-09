<?php

namespace App\Http\Controllers;

use App\Events\UserFollowed;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return view('users.crud.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.crud.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = User::create($this->validateCreateRequest());

        $this->storeImage($user);

        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        return view('users.crud.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

        return view('users.crud.update', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {

        $user->update($this->validateUpdateRequest());

        $this->storeImage($user);
        return redirect("users");
    }







    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/users');
    }

    private function validateCreateRequest()
    {
        return
            tap(request()->validate([
                'name' => 'required|max:255',
                'birthdate' => 'required|date_format:d-m-Y|before:today',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|unique:users,username',
                'password' => 'required|min:8|confirmed',
            ]), function () {
                if (request()->hasFile('pfp')) {
                    request()->validate([
                        'pfp' => 'file|image|max:5000',
                    ]);
                }
            });
    }

    private function validateUpdateRequest()
    {
        return
            tap(request()->validate([
                'name' => 'required|max:255',
                'birthdate' => 'required|date_format:d-m-Y|before:today',
                'email' => 'required|email',
                // 'email' => 'required|email|unique:users,email',
                // 'username' => 'required|unique:users,username',
                // 'password' => 'required|min:8|confirmed',
            ]), function () {
                if (request()->hasFile('pfp')) {
                    request()->validate([
                        'pfp' => 'file|image|max:5000',
                    ]);
                }
            });
    }

    private function storeImage($user)
    {
        if (request()->has('pfp')) {
            $user->update([
                'pfp' => request()->pfp->store('uploads', 'public'),
            ]);
        }
    }

    public function follow($followedUserId, $sessionUserId)
    {
        $user = User::findOrFail($followedUserId);
        $user->dynamicFollow($followedUserId, $sessionUserId, 'followed');
        $user->dynamicFollow($sessionUserId, $followedUserId, 'followers');

        //we are creating the followerId to it on to the $notifiable object in App\Notifications\Follow;

        $user['followerId'] = $sessionUserId;

        event(new UserFollowed($user));
        // unset($user['followerId']);
        return redirect('/users'); 
    }

    public function unfollow($followedUserId, $sessionUserId)
    {
        $user = User::findOrFail($followedUserId);
        $user->dynamicUnfollow($followedUserId, $sessionUserId, 'followed');
        $user->dynamicUnfollow($sessionUserId, $followedUserId, 'followers');
        return redirect('/users'); 

    }

    public function notification_preference($userId, $sessionUserId, $index) {
        
        //fetch the user of $userId
        $user = User::findOrFail($userId);

        //assign the user array to a variable
        $userArray= $user->notification_preferences;
        
        // if the array contains the session user id, delete it
        if (($key = array_search($sessionUserId, $userArray[$index])) !== false) {
            unset($userArray[$index][$key]);
        } else {//If it doesn't contain it, add it
            array_push($userArray[$index], $sessionUserId);
        }

        //update the user array
        $user->notification_preferences = $userArray;

        $user->save();

        return redirect('/users'); 
    }
}
