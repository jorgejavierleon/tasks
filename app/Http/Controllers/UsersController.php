<?php

namespace App\Http\Controllers;

use App\Http\Responses\FractalResponse;
use App\Repositories\UserRepository;
use App\Transformers\UsersTransformer;
use Illuminate\Http\Request;

class UsersController extends ApiController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UsersController constructor.
     * @param FractalResponse $fractal
     * @param UserRepository $userRepository
     */
    public function __construct(FractalResponse $fractal, UserRepository $userRepository)
    {
        parent::__construct($fractal);
        $this->userRepository = $userRepository;
    }

    /**
     * @return array
     */
    public function index()
    {
        $users = $this->userRepository->all();
        return $this->respondWithCollection($users, new UsersTransformer());
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);
        if(!$user){
            return $this->errorNotFound();
        }
        return $this->respondWithItem($user, new UsersTransformer());
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->userRepository->rules());

        $user = $this->userRepository->create($request);
        $this->setStatusCode(201);
        return $this->respondWithItem($user, new UsersTransformer());
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->userRepository->find($id);
        if(!$user){
            return $this->errorNotFound();
        }
        $this->userRepository->update($request, $user);

        return $this->respondWithItem($user, new UsersTransformer());
    }
}