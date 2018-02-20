<?php

namespace Zoomyboy\Tests\Lib;

use GuzzleHttp\Client;
use PHPUnit\Framework\Assert;

class Email {

	public $content;
	public $attachments = [];

	public function __construct($content) {
		$this->content = $content;
	}

	public function __get($attr) {
		return $this->content->{$attr};
	}

    public function getGreeting() {
        preg_match_all('/<h1 style="[^"]+">(.*)<\/h1>/', $this->content->html_body, $matches);
        return $matches[1][0];
    }

    public function assertGreeting($greeting) {
        preg_match_all('/<h1 style="[^"]+">(.*)<\/h1>/', $this->content->html_body, $matches);
       ;
		Assert::assertEquals($greeting, $matches[1][0], 'Failed asserting that the actual Greting '.$matches[1][0].' matches the expected Greeting '.$greeting.'.');
    }

	public function getAction() {
		preg_match_all('/<a href="([^"]+)".*class="button.*>([^<]+)<\/a>/', $this->html_body, $matches, PREG_SET_ORDER);
		return (object) [
			'href' => $matches[0][1],
			'text' => $matches[0][2]
		];
	}

	public function getActions() {
		preg_match_all('/<a href="([^"]+)".*class="button.*>([^<]+)<\/a>/', $this->html_body, $matches, PREG_SET_ORDER);
        return collect(array_map(function($map) {
            return (object) [
                'href' => $map[1],
                'text' => $map[2]
            ];
        }, $matches));
	}

	public function setAttachments() {
		$client = new Client([
			'base_uri' => 'https://mailtrap.io/api/v1/'
		]);

		$request = $client->request('GET', 'inboxes/'.env('MAILTRAP_INBOX').'/messages/'.$this->id.'/attachments', [
			'headers' => [
				'Authorization' => 'Bearer '.env('MAILTRAP_JWT')
			]
		]);

		$this->attachments = array_map(function($file) {
			return $file->filename;
		}, json_decode((string)$request->getBody()));

		return $this;
	}

    public function actionByTitle($title) {
        $action = $this->getActions()->first(function($a) use ($title) {
            return $a->text == $title;
        });

        return $action;
    }
}
