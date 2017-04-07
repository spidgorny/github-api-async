<?php

namespace GitGuzzle;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class TestGuzzle {

	function render() {

		$client = new Client([
			'proxy' => '',
			'proxy2' => [
				'http' => NULL,
				'https' => NULL,
				'no' => ['localhost', 'localhost:8081'],
			]
		]);

		$requests = function ($total) {
			$uri = 'http://localhost:8081/slawa/guzzle/htdocs/sleep.php';
			for ($i = 0; $i < $total; $i++) {
				echo 'Start ', $i, BR;
				yield new Request('GET', $uri);
			}
		};

		$pool = new Pool($client, $requests(100), [
			'concurrency' => 5,
			'fulfilled' => function (Response $response, $index) {
				// this is delivered each successful response
				echo $index, TAB, $response->getStatusCode(), BR;
			},
			'rejected' => function (ClientException $reason, $index) {
				// this is delivered each failed request
				echo $index, TAB, $reason->getMessage(), BR;
			},
		]);

		// Initiate the transfers and create a promise
		$promise = $pool->promise();

		// Force the pool of requests to complete.
		$promise->wait();
	}

}
