<?php

namespace Zoomyboy\Tests\Traits;

use Zoomyboy\Tests\Helpers\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\PersonalAccessClient;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Session;

trait HandlesApiCalls {
    public $token;

    public function bootPassport($user = null, $client = 'API') {
        $this->runMigration('auth_codes_table');
        $this->runMigration('access_tokens_table');
        $this->runMigration('refresh_tokens_table');
        $this->runMigration('oauth_clients_table');
        $this->runMigration('oauth_personal_access_clients_table');

        Artisan::call('passport:client', [
            '--personal' => true,
            '--name' => $client
        ]);

        $user = $user ?: $this->create('User');
        $this->token = $user->createToken($client)->accessToken;
    }

    public function getApi($to, $data=[]) {
        return $this->getJson($this->apiPrefix.$to, $data);
    }

    public function visit($url) {
        return $this->get($url);
    }

    public function postApi($to, $data=[], $headers = []) {
        return $this->postJson($this->apiPrefix.$to, $data, $headers);
    }

    public function deleteApi($to, $data=[]) {
        return $this->deleteJson($this->apiPrefix.$to, $data);
    }

    public function patchApi($to, $data) {
        return $this->postJson($this->apiPrefix.$to, array_merge($data, ['_method' => 'patch']));
    }

    /**
     * Create the test response instance from the given response.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return \Tests\Helpers\Response
     */
    protected function createTestResponse($response)
    {
        return Response::fromBaseResponse($response);
    }

    public function postRealApi($url, $data = [], $token = null) {
        $token = $token ?: $this->token;

        $call = $this->postApi($url, $data, [
            'Authorization' => 'Bearer '.$token
        ]);

        return $call;
    }
}
