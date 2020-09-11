<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\UserFollowed;
use App\Post;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        return $user;
        
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
        
        $favoritePosts = [];
        $followedUsers = [];
        $following = [];
        $likedPosts = [];
        $likedPostsIds = $user->liked['posts'];
        $likedComments = [];
        $likedCommentsIds = $user->liked['posts'];

        if(isset($user->favorites)){
            foreach ($user->favorites as $favoriteId) {
                $favoritePost = Post::findOrFail($favoriteId);
                array_push($favoritePosts, $favoritePost);
            }
            $user['favorite_posts'] = $favoritePosts;
        } else {
            $user['favorite_posts'] = [];
        }

        if(count($likedPostsIds) > 0){
            foreach ($likedPostsIds as $likedPostId) {
                $likedPost = Post::findOrFail($likedPostId);
                array_push($likedPosts, $likedPost);
            }
            $user['liked_posts'] = $likedPosts;
        } else {
            $user['liked_posts'] = [];
        }

        if(count($likedCommentsIds) > 0){
            foreach ($likedCommentsIds as $likedCommentId) {
                $likedComment = Comment::findOrFail($likedCommentId);
                $likedComment['user'] = $likedComment->user;
                array_push($likedComments, $likedComment);

            }
            $user['liked_comments'] = $likedComments;
        } else {
            $user['liked_comments'] = [];
        }

        if(isset($user->followed)){
            foreach ($user->followed as $followedId) {
                $followedUser = User::findOrFail($followedId);
                array_push($followedUsers, $followedUser);
            }
            $user['followedUsers'] = $followedUsers;
        } else {
            $user['followedUsers'] = [];
        }

        if(isset($user->followers)){
            foreach ($user->followers as $followersId) {
                $follower = User::findOrFail($followersId);
                array_push($following, $follower);
            }
            $user['following'] = $following;
        } else {
            $user['following'] = [];
        }


        if(isset($user->posts)){
            $user['posts'] = $user->post;
        } else {
            $user['posts'] = [];
        }

        // if($user->followers == null){
        //     $user['followers'] = [];
        // } 

        // if($user->followed  == null){
        //     $user['followed'] = [];
        // } 

        // return view('users.crud.index', compact('users'));
        
        // return view('users.crud.show', compact('user'));
        return $user;
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
    public function update(Request $request, User $user)
    {
        
        
        $user->update(
            $request->validate([
                'name' => 'required_without_all:username,email,bio,birthdate,phone_number,gender, password,pfp',
                'email' => 'required_without_all:name,username,bio,birthdate,phone_number,gender, password,pfp',
                'birthdate' => 'required_without_all:name,username,email,bio,phone_number,gender, password,pfp',
                'bio' => 'required_without_all:name,username,email,birthdate,phone_number,gender, password,pfp',
                'username' => 'required_without_all:name,email,bio,birthdate,phone_number,gender, password,pfp',
                'gender' => 'required_without_all:name,username,email,bio,birthdate,phone_number, password,pfp',
                'phone_number' => 'required_without_all:name,username,email,bio,birthdate,gender, password,pfp',
                'password' => 'required_without_all:name,username,email,bio,birthdate,gender,phone_number,pfp',
                'pfp' => 'required_without_all:name,username,email,bio,birthdate,gender,phone_number,password',
                // 'password' => 'required|min:8|confirmed',
                ]),
        );  

        $request->merge([
            'password' => Hash::make($request->password),
        ]);
        // 'name' => '',
        //         'email' => '',
        //         'birthdate' => '',
        //         'bio' => '',
        //         'username' => '',
        //         'gender' => '',
        //         'phone_number' => '',
        //         'password' => '',
        //         'pfp' => '',
        // $this->storeImage($user);

        return $user;
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
            'birthdate' => 'required_without_all:name,username,email,bio,phone_number,gender',
            'bio' => 'required_without_all:name,username,email,birthdate,phone_number,gender',
            'username' => 'required_without_all:name,email,bio,birthdate,phone_number,gender',
            'gender' => 'required_without_all:name,username,email,bio,birthdate,phone_number',
            'phone_number' => 'required_without_all:name,username,email,bio,birthdate,gender',
            // 'password' => 'required|min:8|confirmed',
            ]), 
                function () {
                    if (request()->hasAny('name')) {
                        request()->validate([
                            'name' => 'required_without_all:username,email,bio,birthdate,phone_number,gender',
                        ]);
                    }
                    if (request()->hasAny('email')) {
                        request()->validate([
                            'email' => 'required_without_all:name,username,bio,birthdate,phone_number,gender',
                        ]);
                    }
        });
    }

    private function storeImage($user)
    {
        if (request()->has('pfp')) {
            $user->update([
                'pfp' => request()->pfp
            ]);
        }
    }

    public function follow($followedUserId, $sessionUserId)
    {
        $user = User::findOrFail($sessionUserId);
        
        if (!isset($user['followed']) || !in_array($followedUserId, $user['followed'])) {
            $user->dynamicFollow($followedUserId, $sessionUserId, 'followed');
            $user->dynamicFollow($sessionUserId, $followedUserId, 'followers');
        }
        

        //we are creating the followerId to access it on to the $notifiable object in App\Notifications\Follow;

        $user['followerId'] = $sessionUserId;

        event(new UserFollowed($user));
        // unset($user['followerId']);
        $user = User::findOrFail($sessionUserId);
        return $user; 
    }

    public function unfollow($followedUserId, $sessionUserId)
    {
        $user = User::findOrFail($sessionUserId);
        if (isset($user['followed']) && in_array($followedUserId, $user['followed'])) {
            $user->dynamicUnfollow($followedUserId, $sessionUserId, 'followed');
            $user->dynamicUnfollow($sessionUserId, $followedUserId, 'followers');
        }
        
        $user = User::findOrFail($sessionUserId);
        return $user; 

    }

    public function notification_preference($userId, $sessionUserId, $index) {
        
        //fetch the user of $userId
        $user = User::findOrFail($userId);

        //assign the user array to a variable
        $userArray= $user->notification_preferences;
        // if the array contains the session user id, delete it
        // we need to use !== because we need to check for the datatype as well
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
