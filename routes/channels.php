<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('orders', function ($user) {
    return true;
});

Broadcast::channel('tasks.{projectId}', function ($user, $projectId) {
    // if($user->id == 1)
    // {
    //     $canAccess = [1,3];
    // }
    // else
    // {
    //     $canAccess = [2,4];
    // }
    // return in_array($projectId, $canAccess);
    return true;
});
