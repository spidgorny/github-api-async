<?php

namespace GitGuzzle;

use Github\Api\Repo;
use Github\Client;

class TestGithub {

	public $username = 'ornicar';

	function render() {
		$client = new Client();
		/** @var \Github\Api\User $user */
		$user = $client->api('user');
		$repositories = $user->repositories($this->username);

		/** @var Repo $repo */
		$repo = $client->api('repo');
		foreach ($repositories as $rep) {
			echo $rep['name'], BR;
			$info = $repo->show($this->username, $rep['name']);
			echo TAB, $info['forks'], BR;
		}
	}

}
