<?php

namespace Zoomyboy\Tests\Traits;

use App\User;

trait CreatesModels {
	public function makeUser($name, $mail, $initial) {
		return factory(User::class)->create(['name' => $name, 'email' => $mail, 'initial' => $initial]);
	}

	public function create($cls, $data = []) {
		$className = 'App\\'.ucfirst($cls);
		$this->assertTrue(class_exists($className), 'Failed asserting that class \\App\\'.ucfirst($cls).' exists.');
		return factory($className)->create($data);
	}

	public function createMany($cls, $num, $data = []) {
		$className = 'App\\'.ucfirst($cls);

		$this->assertTrue(class_exists($className), 'Failed asserting that class \\App\\'.ucfirst($cls).' exists.');

		$collection = collect([]);

		foreach(range(0, $num-1) as $i) {
			$collection->push(factory($className)->create($data[$i] ?? []));
		}

		return $collection;
	}

	public function make($cls, $data = []) {
		$className = 'App\\'.ucfirst($cls);
		$this->assertTrue(class_exists($className), 'Failed asserting that class \\App\\'.ucfirst($cls).' exists.');
		return factory($className)->make($data);
	}
}
