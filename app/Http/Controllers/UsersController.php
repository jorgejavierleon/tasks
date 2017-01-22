<?php

namespace App\Http\Controllers;

use App\Http\Responses\FractalResponse;
use App\Repositories\TaskRepository;
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
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * UsersController constructor.
     * @param FractalResponse $fractal
     * @param UserRepository $userRepository
     * @param TaskRepository $taskRepository
     */
    public function __construct(
        FractalResponse $fractal,
        UserRepository $userRepository,
        TaskRepository $taskRepository
    )
    {
        parent::__construct($fractal);
        $this->userRepository = $userRepository;
        $this->taskRepository = $taskRepository;
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

    /**
     * @param $userId
     * @param $taskId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTask($userId, $taskId)
    {
        $user = $this->userRepository->find($userId);
        $task = $this->taskRepository->find($taskId);
        if(!$user || !$task){
            return $this->errorNotFound();
        }

        $this->userRepository->addTask($user, $task);

        return $this->respondWithItem($user, new UsersTransformer(), $include = 'tasks');
    }

    /**
     * @param $userId
     * @param $taskId
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function removeTask($userId, $taskId)
    {
        $user = $this->userRepository->find($userId);
        $task = $this->taskRepository->find($taskId);
        if(!$user || !$task){
            return $this->errorNotFound();
        }

        $this->userRepository->removeTask($user, $task);

        return response(null, 204);
    }

    /**
     * @param $id
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);
        if(!$user){
            return $this->errorNotFound();
        }
        $this->userRepository->delete($user);

        return response(null, 204);
    }
}