<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new user avatar
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'avatar' => 'required|image',
        ]);

        auth()->user()->update([
           'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);
        return response([], 204);
    }
}
