<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'favorites' => 'array',
        'liked' => 'array',
    ];

    // $databaseIndex is the index of the User liked column in the database

    public function dynamicLike($likedObject, $userId, $databaseIndex)
    {
        // When like button is clicked, increment the likes attribute by 1
        $likedObject->likes += 1;

        // update the table->likes column
        $likedObject->update(array('likes' => $likedObject->likes));

        // Fetch from database the user who clicked on the like button
        $user = User::findorFail($userId);

        // Assign the User's liked attribute to a variable
        $liked = $user->liked;

        //Push the id of the liked object into the declared variable, with the appropriate index ex($liked['post' or 'comment']) 
        array_push($liked[$databaseIndex . 's'], $likedObject->id);

        //update the User's liked attribute 
        $user->liked = $liked;

        $user->save();

        // return redirect('/' . $object . 's'); this only works in controller ??? Iliass
    }

    public function dynamicUnlike($unlikedObject, $userId, $databaseIndex)
    {
        // When unlike button is clicked, decrement the likes attribute by 1
        $unlikedObject->likes -= 1;

        // update the table->likes column
        $unlikedObject->update(array('likes' => $unlikedObject->likes));

        // Fetch from database the user who clicked on the like button
        $user = User::findorFail($userId);

        // Assign the User's liked attribute to a variable
        $liked = $user->liked;

        //Find the unliked object's ID within the $liked array, then assign it's index to $key
        $key = array_search($unlikedObject->id, $liked[$databaseIndex . 's']);

        //Remove the unliked object's ID from the array
        unset($liked[$databaseIndex . 's'][$key]);


        //update the User's liked attribute 
        $user->liked = $liked;

        $user->save();

        // return redirect('/' . $object . 's'); this only works in controller ??? illias
    }

    public function dynamicFollow($idToAdd, $idToReceive, $storage)
    {

        // Fetch from database the user who will receive the id
        $sessionUser = User::findorFail($idToReceive);

        // Fetch the user's attribute who will store the id and assign it to a variable $followedUsers
        $followedUsers = json_decode($sessionUser->$storage);

        // if $followedUsers is not an array, create an array with the $idToAdd
        if ($followedUsers == null) {
            $followedUsers = [$idToAdd];
        } else { // if $followedUsers is already an array, push the $idToAdd
            array_push($followedUsers, $idToAdd);
        }



        //update the User's receiver attribute 
        $sessionUser->$storage = $followedUsers;
        $sessionUser->save();
    }

    public function dynamicUnfollow($idToRemove, $idToReceive, $storage)
    {

        // Fetch from database the user who will receive the id
        $sessionUser = User::findorFail($idToReceive);

        // Fetch the user's attribute who will store the id and assign it to a variable $followedUsers
        $followedUsers = json_decode($sessionUser->$storage, true);

        //Find the $idToRemove within the attribute array, then assign it's index to $key
        $key = array_search($idToRemove, $followedUsers);
        //Remove the $idToRemove from the array
        unset($followedUsers[$key]);

        //update the User's receiver attribute 
        $sessionUser->$storage = $followedUsers;
        $sessionUser->save();
    }
}
