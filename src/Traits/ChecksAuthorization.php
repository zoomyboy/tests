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

    public function assertValidationFails($class, $values, $with = []) {
        $request = (new $class())->replace($values);
        $validator = Validator::make($values, $request->rules());

        $this->assertTrue($validator->fails(), 'Validation expected to fail, but it succeeded with Request '.print_r($values, true));
        $this->assertEmpty(
            array_diff($with, array_keys($validator->failed())),
            "Failed asserting that Validation failed with ".implode(', ', $with)."\n
            Request: ".print_r($values, true)."\n
            Failed Rules: ".implode(', ', array_keys($validator->failed()))
        );
    }

    public function assertValidationPasses($class, $values = []) {
        $request = new $class([], $values, [], [], [], ['REQUEST_METHOD' => 'PATCH']);
        $validator = Validator::make($values, $request->rules());

        $this->assertTrue($validator->passes(), 'Validation expected to pass, but it failed with Request '.print_r($values, true).' and Rules '.print_r($validator->failed(), true));
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
