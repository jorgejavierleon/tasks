<?php

namespace App\Http\Controllers;

use App\Transformers\UsersTransformer;
use App\User;

class UsersController extends ApiController
{

    /**
     * @return array
     */
    public function index()
    {
        $users = User::all();
        return $this->respondWithCollection($users, new UsersTransformer());
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        $user = User::find($id);
        if(!$user){
            return $this->errorNotFound();
        }
        return $this->respondWithItem($user, new UsersTransformer());
    }
}