<?php

namespace Zoomyboy\Tests\Lib;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert;

class EmailCollection extends Collection {
	public function to($email) {
		return $this->filter(function($mail) use ($email) {
			return $mail->to_email == $email;
		});
	}

	public function withAttachment($filename) {
		return $this->filter(function($mail) use ($filename) {
			return in_array($filename, $mail->attachments);
		});
	}

	public function withSubject($s) {
		return $this->filter(function($mail) use ($s) {
			return $mail->subject == $s;
		});
	}

	public function wasSent($count = 1) {
		Assert::assertCount($count, $this);
	}
}
