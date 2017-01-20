<?php


trait WithoutMiddleware
{
    /**
     * Prevent all middleware from being executed for this test class.
     *
     */
    public function setUp()
    {
        parent::setUp();
        if (method_exists($this, 'withoutMiddleware')) {
            $this->withoutMiddleware();
        }
    }
}