<?php


use App\Transformers\UsersTransformer;
use App\User;
use Laravel\Lumen\Testing\DatabaseTransactions;
use League\Fractal\TransformerAbstract;

class UsersTrasnformerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     * @test
     */
    public function it_can_be_initialized()
    {
        $transformer = new UsersTransformer();
        $this->assertInstanceOf(TransformerAbstract::class, $transformer);
    }

    /**
     * @return void
     * @test
     */
    public function it_transform_users()
    {
        $user = factory(User::class)->create();
        $transformer = new UsersTransformer();
        $transform = $transformer->transform($user);

        $this->assertArrayHasKey('id', $transform);
        $this->assertArrayHasKey('firstname', $transform);
        $this->assertArrayHasKey('lastname', $transform);
        $this->assertArrayHasKey('email', $transform);
    }

}