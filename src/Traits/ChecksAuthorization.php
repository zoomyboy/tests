<?php

namespace Zoomyboy\Tests\Traits;

use Validator;
use \Mockery as M;

trait ChecksAuthorization {
    public function assertRequestDenies($request) {
        $request->setContainer($this->app);

        $this->app->instance(get_class($request), $request);
        $this->assertFalse($request->authorize());
    }

    public function mockUser($rights = []) {
        $user = M::mock($this->getUserModel());

        foreach ($rights as $right => $access) {
            $user->shouldReceive('hasRight')->with($right)->andReturn($access);
        }

        return $user;
    }

    public function assertRequestFails($class, $values = [], $messages = null) {
        $request = new $class([], $values);
        $validator = Validator::make($values, $request->rules());

        $this->assertTrue($validator->fails());
    }

    public function assertRequestPasses($class, $values = [], $messages = null) {
        $request = new $class([], $values);
        $validator = Validator::make($values, $request->rules());

        $this->assertFalse($validator->fails());
    }

    public function getUserModel() {
        return property_exists($this, 'fakeUserModel')
            ? $this->fakeUserModel
            : '\App\User';
    }

    public function assertRequestGrants($request) {
        $request->setContainer($this->app);

        $this->app->instance(get_class($request), $request);
        $this->assertTrue($request->authorize());
    }
}
