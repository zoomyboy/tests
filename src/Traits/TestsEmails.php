<?php

namespace Zoomyboy\Tests\Traits;

use Zoomyboy\Tests\Lib\EmailCollection;
use GuzzleHttp\Client;
use Zoomyboy\Tests\Lib\Email;

trait TestsEmails {
	public function clearMailtrap() {
		$client = new Client([
			'base_uri' => 'https://mailtrap.io/api/v1/'
		]);
		$request = $client->request('PATCH', 'inboxes/165153/clean', [
			'headers' => [
				'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ0b2tlbiI6ImQ3NDJjMDc5Y2M5MzBjZGNiMDU5YjZhZDQxMGI1NjA4In0.8r6hgvUGekf_uginRGFQAJVYGQ7gr1TRqRJINDYXrzE_BFPGJ9zZPAxEl6mWZxGugfpAALyIheCZ8R7fmpKbLg'
			]
		]);
	}

	public function assertMailtrap() {
		$client = new Client([
			'base_uri' => 'https://mailtrap.io/api/v1/'
		]);
		$request = $client->request('GET', 'inboxes/165153/messages', [
			'headers' => [
				'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ0b2tlbiI6ImQ3NDJjMDc5Y2M5MzBjZGNiMDU5YjZhZDQxMGI1NjA4In0.8r6hgvUGekf_uginRGFQAJVYGQ7gr1TRqRJINDYXrzE_BFPGJ9zZPAxEl6mWZxGugfpAALyIheCZ8R7fmpKbLg'
			]
		]);
		return (new EmailCollection(json_decode((string)$request->getBody())))
			->map(function($email) {
				return (new Email($email))->setAttachments();
			});
	}

	public function assertMailtrapCount($count) {
		$this->assertEquals($count, $this->assertMailtrap()->count());
	}

	public function assertEmailSubject($subject, $email) {
		$this->assertEquals($subject, $email->subject, 'Failed asserting that Subject '.$email->subject.' is '.$subject);
	}

	public function assertEmailGreeting($greeting, $email) {
		$this->assertEquals(1, preg_match('/<h1 style=[^>]*>'.preg_quote($greeting).'<\/h1>/', $email->html_body), 'Failed asserting that Email has Greeting '.$greeting);
	}
}
