<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ControllerTestCase extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;
}