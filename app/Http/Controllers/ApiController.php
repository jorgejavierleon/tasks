<?php


namespace App\Http\Controllers;

use App\Http\Responses\FractalResponse;
use League\Fractal\TransformerAbstract;

class ApiController extends Controller
{
    protected $statusCode = 200;
    /**
     * @var FractalResponse
     */
    protected $fractal;

    /**
     * ApiController constructor.
     * @param FractalResponse $fractal
     */
    public function __construct(FractalResponse $fractal)
    {
        $this->fractal = $fractal;
        $this->fractal->parseIncludes();
    }

    /**
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @param null $includes
     * @return array
     */
    protected function respondWithItem($data, TransformerAbstract $transformer, $includes = null)
    {
        if($includes){
            $this->fractal->parseIncludes($includes);
        }
        $rootScope = $this->fractal->item($data, $transformer);
        return $this->respondWithArray($rootScope);
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @param null $includes
     * @return array
     */
    protected function respondWithCollection($data, TransformerAbstract $transformer, $includes = null)
    {
        if($includes){
            $this->fractal->parseIncludes($includes);
        }
        $rootScope = $this->fractal->collection($data, $transformer);
        return $this->respondWithArray($rootScope);
    }

    /**
     * @param $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function respondWithError($message)
    {
        return $this->respondWithArray([
            'error' => [
                'http_code' => $this->statusCode,
                'message' => $message,
            ]
        ]);
    }

    /**
     * @param string $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function errorNotFound($message = 'Resource not found')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * @param array $array
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        return response()->json($array, $this->statusCode, $headers);
    }
}