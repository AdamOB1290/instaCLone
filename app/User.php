<?php

namespace App;

use App\Notification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany(Post::class)->orderByDesc('likes');

    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'notifiable_id');

    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'sender_id');
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
        'followed' => 'array',
        'followers' => 'array',
        'notification_preferences'=> 'array',
    ];

    // $databaseIndex is the index of the User liked column in the database

    public function dynamicLike($likedObject, $userId, $databaseIndex)
    {
        $objectLikes=$likedObject->likes;
        // When like button is clicked, increment the likes attribute by 1
        if ($objectLikes == null) {
            $objectLikes = [(int)$userId];
        } else { // if $objectLikes is already an array, push the $userId
            if (array_search((int)$userId, $objectLikes) === false) {
                array_push($objectLikes, (int)$userId);
            }

            
        }

        // update the table->likes column
        $likedObject->update(array('likes' => $objectLikes));

        // Fetch from database the user who clicked on the like button
        $user = User::findorFail($userId);

        // Assign the User's liked attribute to a variable
        $liked = $user->liked;
        if ( array_search($likedObject->id, $liked[$databaseIndex . 's']) === false ) {
            //Push the id of the liked object into the declared variable, with the appropriate index ex($liked['post' or 'comment']) 
            array_push($liked[$databaseIndex . 's'], $likedObject->id);

            //update the User's liked attribute 
            $user->liked = $liked;

            $user->save();
        }
        return $user;

        // return redirect('/' . $object . 's'); this only works in controller ??? Iliass
    }

    public function dynamicUnlike($unlikedObject, $userId, $databaseIndex)
    {
        $objectLikes = $unlikedObject->likes;
        if (!isset($objectLikes)) {
            $objectLikes= [];
        }
        // When unlike button is clicked, decrement the likes attribute by 1
        if (($key = array_search((int)$userId, $objectLikes)) !== false) {
            
            //Remove the $idToRemove from the array
            array_splice($objectLikes, $key, 1);
            
        }

        // update the table->likes column
        $unlikedObject->update(array('likes' => $objectLikes));

        // Fetch from database the user who clicked on the like button
        $user = User::findorFail($userId);

        // Assign the User's liked attribute to a variable
        $liked = $user->liked;
        //Find the unliked object's ID within the $liked array, then assign it's index to $key
        $key = array_search($unlikedObject->id, $liked[$databaseIndex . 's']);


        if (array_search($unlikedObject->id, $liked[$databaseIndex . 's']) !== false) {
            //Remove the unliked object's ID from the array
            array_splice($liked[$databaseIndex . 's'], $key, 1);
            //update the User's liked attribute 
            $user->liked = $liked;

            $user->save();
        }
        

        

        // return redirect('/' . $object . 's'); this only works in controller ??? illias
        return $user;

    }

    public function dynamicFollow($idToAdd, $sessionUserId, $storage)
    {

        // Fetch from database the user who will receive the id
        $sessionUser = User::findorFail($sessionUserId);

        

        // Fetch the user's attribute who will store the id and assign it to a variable $followedUsers
        $followedUsers = $sessionUser->$storage;

           // if $followedUsers is not an array, create an array with the $idToAdd
            if ($followedUsers == null) {
                $followedUsers = [(int)$idToAdd];
            } else { // if $followedUsers is already an array, push the $idToAdd
                
                if (array_search((int)$idToAdd, $followedUsers) == false) {
                    
                    array_push($followedUsers, (int)$idToAdd);
                }

                
            }

        //update the User's receiver attribute 
        $sessionUser->$storage = $followedUsers;
        $sessionUser->save(); 
    }

    public function dynamicUnfollow($idToRemove, $sessionUserId, $storage)
    {

        // Fetch from database the user who will receive the id
        $sessionUser = User::findorFail($sessionUserId);

        // Fetch the user's attribute who will store the id and assign it to a variable $followedUsers
        $followedUsers = $sessionUser->$storage;
        
        //Find the $idToRemove within the attribute array, then assign it's index to $key
        if (($key = array_search((int)$idToRemove, $followedUsers)) !== false) {
            
            //Remove the $idToRemove from the array
            array_splice($followedUsers, $key, 1);

            //update the User's receiver attribute 
            $sessionUser->$storage = $followedUsers;
            $sessionUser->save();
        }
        
        
    }

    
}
