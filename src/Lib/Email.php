<?php

namespace Zoomyboy\Tests\Lib;

class Email {

	public $content;

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
}
