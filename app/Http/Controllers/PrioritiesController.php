<?php

namespace App\Http\Controllers;

use App\Http\Responses\FractalResponse;
use App\Repositories\PriorityRepository;
use App\Transformers\PrioritiesTrasnformer;
use Illuminate\Http\Request;

class PrioritiesController extends ApiController
{
    /**
     * @var PriorityRepository
     */
    private $repository;

    /**
     * PrioritiesController constructor.
     * @param FractalResponse $fractal
     * @param PriorityRepository $repository
     */
    public function __construct(FractalResponse $fractal, PriorityRepository $repository)
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
        return $this->respondWithCollection($priorities, new PrioritiesTrasnformer());
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        $priority = $this->repository->find($id);
        if (!$priority) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($priority, new PrioritiesTrasnformer());
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->repository->rules());

        $priority = $this->repository->create($request);
        $this->setStatusCode(201);
        return $this->respondWithItem($priority, new PrioritiesTrasnformer());
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id)
    {
        $priority = $this->repository->find($id);
        if (!$priority) {
            return $this->errorNotFound();
        }
        $this->repository->update($request, $priority);

        return $this->respondWithItem($priority, new PrioritiesTrasnformer());
    }

    /**
     * @param $id
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $priority = $this->repository->find($id);
        if (!$priority) {
            return $this->errorNotFound();
        }
        $this->repository->delete($priority);

        return response(null, 204);
    }
}
