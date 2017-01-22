<?php

namespace App\Http\Controllers;

use App\Http\Responses\FractalResponse;
use App\Repositories\TaskRepository;
use App\Transformers\TasksTransformer;
use Illuminate\Http\Request;

class TasksController extends ApiController
{
    /**
     * @var TaskRepository
     */
    private $repository;

    /**
     * PrioritiesController constructor.
     * @param FractalResponse $fractal
     * @param TaskRepository $repository
     */
    public function __construct(FractalResponse $fractal, TaskRepository $repository)
    {
        parent::__construct($fractal);
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function index()
    {
        $priorities = $this->repository->all();
        return $this->respondWithCollection($priorities, new TasksTransformer());
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        $task = $this->repository->find($id);
        if(!$task){
            return $this->errorNotFound();
        }
        return $this->respondWithItem($task, new TasksTransformer());
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->repository->rules());

        $task = $this->repository->create($request);
        $this->setStatusCode(201);
        return $this->respondWithItem($task, new TasksTransformer());
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id)
    {
        $task = $this->repository->find($id);
        if(!$task){
            return $this->errorNotFound();
        }
        $this->repository->update($request, $task);

        return $this->respondWithItem($task, new TasksTransformer());
    }

    /**
     * @param $id
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $task = $this->repository->find($id);
        if(!$task){
            return $this->errorNotFound();
        }
        $this->repository->delete($task);

        return response(null, 204);
    }
}