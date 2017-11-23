<?php

namespace Zoomyboy\Tests\Lib;

use Illuminate\Support\Collection;

class EmailCollection extends Collection {
	public function to($email) {
		return $this->first(function($mail) use ($email) {
			return $mail->to_email == $email;
		});
	}
}
