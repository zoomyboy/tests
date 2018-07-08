<?php

namespace Zoomyboy\Tests\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;

trait FakesGuzzle {
    public $container = [];

    public function fakeGuzzle($requests) {
        $history = Middleware::history($this->container);

        $mock = new MockHandler($requests);

        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $client = new Client(['handler' => $stack]);
        $this->app->instance(Client::class, $client);
    }
}
