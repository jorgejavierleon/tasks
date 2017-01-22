<?php

use Illuminate\Http\Request;
use League\Fractal\Serializer\SerializerAbstract;
use Mockery as m;
use League\Fractal\Manager;
use App\Http\Responses\FractalResponse;

class FractalResponseTest extends TestCase
{
    /**
     * @return void
     * @test
     */
    public function it_can_be_initialized()
    {
        $manager = m::mock(Manager::class);
        $serializer = m::mock(SerializerAbstract::class);
        $request = m::mock(Request::class);

        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once()
            ->andReturn($manager);

        $fractal = new FractalResponse($manager, $serializer, $request);
        $this->assertInstanceOf(FractalResponse::class, $fractal);
    }

    /**
     * @return void
     * @test
     */
    public function it_can_transform_an_item()
    {
        //Request
        $request = m::mock(Request::class);

        // Transformer
        $transformer = m::mock('League\Fractal\TransformerAbstract');

        // Scope
        $scope = m::mock('League\Fractal\Scope');
        $scope->shouldReceive('toArray')
            ->once()
            ->andReturn(['foo' => 'bar']);

        // Serializer
        $serializer = m::mock('League\Fractal\Serializer\SerializerAbstract');

        $manager = m::mock('League\Fractal\Manager');
        $manager->shouldReceive('setSerializer')
            ->with($serializer)
            ->once();
        $manager->shouldReceive('createData')
            ->once()
            ->andReturn($scope);

        $subject = new FractalResponse($manager, $serializer, $request);
        $this->assertInternalType(
            'array',
            $subject->item(['foo' => 'bar'], $transformer)
        );
    }

    /**
     * @return void
     * @test
     */
    public function it_can_transfor_a_collection()
    {
        //Data
        $data = [
            ['foo' => 'bar'],
            ['fizz' => 'buzz']
        ];

        //Request
        $request = m::mock(Request::class);

        // Transformer
        $transformer = m::mock('League\Fractal\TransformerAbstract');

        // Scope
        $scope = m::mock('League\Fractal\Scope');
        $scope->shouldReceive('toArray')
            ->once()
            ->andReturn($data);

        // Serializer
        $serializer = m::mock('League\Fractal\Serializer\SerializerAbstract');

        $manager = m::mock('League\Fractal\Manager');
        $manager->shouldReceive('setSerializer')
            ->with($serializer)
            ->once();

        $manager->shouldReceive('createData')
            ->once()
            ->andReturn($scope);

        $subject = new FractalResponse($manager, $serializer, $request);
        $this->assertInternalType(
            'array',
            $subject->collection($data, $transformer)
        );
    }

    /**
     * @return void
     * @test
     */
    public function it_should_parse_passed_includes_when_passed()
    {
        $serializer = m::mock(SerializerAbstract::class);
        $manager = m::mock(Manager::class);
        $manager->shouldReceive('setSerializer')->with($serializer);
        $manager->shouldReceive('parseIncludes')->with('tasks');

        $request = m::mock(Request::class);
        $request->shouldNotReceive('tasks');

        $subject = new FractalResponse($manager, $serializer, $request);
        $subject->parseIncludes('tasks');
    }
    
    /**
     * @return void
     * @test
     */
    public function it_should_parse_request_query_includes_with_no_arguments()
    {
        $serializer = m::mock(SerializerAbstract::class);
        $manager = m::mock(Manager::class);
        $manager->shouldReceive('setSerializer')->with($serializer);
        $manager->shouldReceive('parseIncludes')->with('tasks');

        $request = m::mock(Request::class);
        $request
            ->shouldReceive('query')
            ->with('include', '')
            ->andReturn('tasks');

        $subject = new FractalResponse($manager, $serializer, $request);
        $subject->parseIncludes('tasks');
    }
}