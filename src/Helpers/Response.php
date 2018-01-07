<?php

namespace Zoomyboy\Tests\Helpers;

use Illuminate\Foundation\Testing\TestResponse;

class Response extends TestResponse {
	use \Illuminate\Validation\Concerns\FormatsMessages;

	public $customMessages = [];

	public function assertSuccess() {
		return $this->assertStatus(200);
	}

	public function assertNotFound() {
		return $this->assertStatus(404);
	}

	public function assertRedirectTo($url) {
		return $this->assertRedirect($url);
	}

	public function assertRedirectedTo($url) {
		return $this->assertRedirect($url);
	}

	public function assertForbidden() {
		return $this->assertStatus(403);
	}

	public function assertUnauthorized() {
		return $this->assertStatus(401);
	}

	public function assertValidationFailedWith(...$field) {
		$this->assertJsonStructure($field)->assertStatus(422);

		return $this;
	}
}	
