<?php

namespace Zoomyboy\Tests\Traits;

use Zoomyboy\Tests\Lib\EmailCollection;
use GuzzleHttp\Client;
use Zoomyboy\Tests\Lib\Email;

trait TestsEmails {
	public function clearMailtrap() {
        if (!env('MAILTRAP_INBOX')) {abort(404, 'You should set the Mailtrap Inbox ID MAILTRAP_INBOX in env first');}
		$client = new Client([
			'base_uri' => 'https://mailtrap.io/api/v1/'
		]);
		$request = $client->request('PATCH', 'inboxes/'.env('MAILTRAP_INBOX').'/clean', [
			'headers' => [
				'Authorization' => 'Bearer '.env('MAILTRAP_JWT')
			]
		]);
	}

	public function assertMailtrap() {
        if (!env('MAILTRAP_INBOX')) {abort(404, 'You should set the Mailtrap Inbox ID MAILTRAP_INBOX in env first');}
		$client = new Client([
			'base_uri' => 'https://mailtrap.io/api/v1/'
		]);

		$request = $client->request('GET', 'inboxes/'.env('MAILTRAP_INBOX').'/messages', [
			'headers' => [
				'Authorization' => 'Bearer '.env('MAILTRAP_JWT')
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
