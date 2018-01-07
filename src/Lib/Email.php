<?php

namespace Zoomyboy\Tests\Lib;

use GuzzleHttp\Client;

class Email {

	public $content;
	public $attachments = [];

	public function __construct($content) {
		$this->content = $content;
	}

	public function __get($attr) {
		return $this->content->{$attr};
	}

	public function getAction() {
		preg_match_all('/<a href="([^"]+)".*class="button.*>([^<]+)<\/a>/', $this->html_body, $matches, PREG_SET_ORDER);
		return (object) [
			'href' => $matches[0][1],
			'text' => $matches[0][2]
		];
	}

	public function setAttachments() {
		$client = new Client([
			'base_uri' => 'https://mailtrap.io/api/v1/'
		]);

		$request = $client->request('GET', 'inboxes/165153/messages/'.$this->id.'/attachments', [
			'headers' => [
				'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ0b2tlbiI6ImQ3NDJjMDc5Y2M5MzBjZGNiMDU5YjZhZDQxMGI1NjA4In0.8r6hgvUGekf_uginRGFQAJVYGQ7gr1TRqRJINDYXrzE_BFPGJ9zZPAxEl6mWZxGugfpAALyIheCZ8R7fmpKbLg'
			]
		]);

		$this->attachments = array_map(function($file) {
			return $file->filename;
		}, json_decode((string)$request->getBody()));

		return $this;
	}
}
