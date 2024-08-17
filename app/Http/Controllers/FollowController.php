<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user) {
        // you can't follow yourself
        if ($user->id === auth()->user()->id) {
            return back()->with('failure', 'You can\'t follow yourself');
        }

        // you can't follow the same user twice
        $exitCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();

        if ($exitCheck > 0) {
            return back()->with('failure', 'You are already following this user');
        }

        $newFollow = new Follow();
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

//        Follow::create([
//            'user_id' => auth()->user()->id,
//            'followedid' => $user->id
//        ]);

        return back()->with('success', 'You are now following ' . $user->username);
    }

    public function removeFollow(User $user) {
        Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();

        return back()->with('success', 'You are no longer following ' . $user->username);
    }
}
