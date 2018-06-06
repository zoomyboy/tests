<?php

namespace Zoomyboy\Tests\Traits;

use Illuminate\Contracts\Debug\ExceptionHandler;
use App\Exceptions\Handler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

trait HandlesExceptions {
    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(\Exception $e) {}
            public function render($request, \Exception $e) {
                if(is_a($e, AuthenticationException::class) && $e->getMessage() == 'Unauthenticated.') {
                    return response('Unauthenticated', 401);
                }

                if(is_a($e, AuthorizationException::class)) {
                    return response('Forbidden', 403);
                }

                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }

    public function assertException($exc, $closure) {
        $thrown = null;

        try {
            call_user_func($closure);
        } catch(\Exception $e) {
            if (! is_a($e, $exc)) {
                throw $e;
            }
            $this->assertInstanceOf($exc, $e, 'Failed asserting that Exception '.$exc.' was thrown.');
            $thrown = $e;
        }

        $this->assertInstanceOf($exc, $thrown, 'Failed asserting that Exception '.$exc.' was thrown.');
    }
}
