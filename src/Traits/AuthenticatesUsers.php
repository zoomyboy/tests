<?php

namespace Zoomyboy\Tests\Traits;

use Laravel\Passport\Passport;

trait AuthenticatesUsers
{
    public function auth($user = null, $guard = 'web')
    {
        $user = $user ?: $this->create('User');
        $this->be($user, $guard);
    }

    public function authAs($user = null, $guard = null)
    {
        $this->auth($user, $guard);
    }

    public function authAsApi($user = null, $guard = null)
    {
        $shouldCreateUser = is_null($user);

        if (method_exists($this, 'beforeAuthUserCreated')
            && $shouldCreateUser) {
            $this->beforeAuthUserCreated($user);
        }

        $user = $user ?: $this->create('User');
        Passport::actingAs($user, [], $guard ?: 'api');

        if (method_exists($this, 'afterAuthUserCreated')
            && $shouldCreateUser) {
            $this->afterAuthUserCreated($user);
        }

        return $user;
    }
}
