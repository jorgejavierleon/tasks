<?php

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

        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once()
            ->andReturn($manager);

        $fractal = new FractalResponse($manager, $serializer);
        $this->assertInstanceOf(FractalResponse::class, $fractal);
    }

    /**
     * @return void
     * @test
     */
    public function it_can_transform_an_item()
    {
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

        $subject = new FractalResponse($manager, $serializer);
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

        $subject = new FractalResponse($manager, $serializer);
        $this->assertInternalType(
            'array',
            $subject->collection($data, $transformer)
        );
    }


}