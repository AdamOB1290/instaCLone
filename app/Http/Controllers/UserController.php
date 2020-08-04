<?php

namespace App\Http\Controllers;

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
        $user = new User;
        // (int) was needed because ids were shown as strings
        $user->dynamicFollow((int)$followedUserId, (int)$sessionUserId, 'followed');
        $user->dynamicFollow((int)$sessionUserId, (int)$followedUserId, 'followers');

        return redirect('/users'); 
    }

    public function unfollow($followedUserId, $sessionUserId)
    {
        $user = new User;
        // (int) was needed because ids were shown as strings
        $user->dynamicUnfollow((int)$followedUserId, (int)$sessionUserId, 'followed');
        $user->dynamicUnfollow((int)$sessionUserId, (int)$followedUserId, 'followers');
        return redirect('/users'); 

    }
}
