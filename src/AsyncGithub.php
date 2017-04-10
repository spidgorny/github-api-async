<?php

namespace GitGuzzle;

use Github\Api\Repo;
use Github\Client as GithubClient;
use Guzzle\Http\Message\RequestFactory;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle6\Client as AdapterClient;
use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\MessageFactoryDiscovery;

class AsyncGithub {

	public $username = 'ornicar';

	/**
	 * @var GithubClient
	 */
	protected $client;

	/**
	 * @var HttpMethodsClient
	 */
	protected $httpClient;

	var $base = 'http://localhost:8081/slawa/guzzle/htdocs/';

	function __construct() {
		//$this->client = new GithubClient(null, null, $this->base);
		//$this->httpClient = $this->client->getHttpClient();
		$this->httpClient = new GuzzleClient([
			'base_uri' => $this->base,
			'proxy' => '',
		]);

		//$hc = $this->httpClient;
		$hc = new AdapterClient($this->httpClient);

		$this->client = GithubClient::createWithHttpClient(
			new	HttpMethodsClient($hc,
				MessageFactoryDiscovery::find()
			)
		);
	}

	function render() {
		/** @var \Github\Api\User $user */
		//$user = $this->client->api('user');
		//$repositories = $user->repositories($this->username);
		$repositories = $this->getFakeRepos();
		echo 'Repos: ', sizeof($repositories), BR;

		$requests = $this->generateRequests($repositories);

		$pool = new Pool($this->httpClient, $requests, [
			'concurrency' => 5,
			'fulfilled' => [$this, 'requestFulfilled'],
			'rejected' => function (ClientException $reason, $index) {
				// this is delivered each failed request
				echo $index, TAB, $reason->getMessage(), BR;
			},
		]);
		$promise = $pool->promise();

		$promise->wait();
		echo 'Done in ',
		number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3), BR;
	}

	function show($username, $repository) {
		$url = 'sleep.php/repos/'.rawurlencode($username).'/'.rawurlencode($repository);

		$requestFactory = MessageFactoryDiscovery::find();
		$request = $requestFactory->createRequest('get', $url, [], NULL);

		return $request;
	}

	/**
	 * @return array
	 */
	private function getFakeRepos(): array {
		$repositories = [
			'ag.vim',
			'ahDoctrineEasyEmbeddedRelationsPlugin',
			'ApcBundle',
			'ape-petition',
			'AssetOptimizerBundle',
			'AvalancheSitemapBundle',
			'backdoor',
			'board-creator',
			'Buzz',
			'casbah',
			'cash',
			'Chess-Variants-Training',
			'chess.js',
			'chessboardjs',
			'chessground',
			'chessground-examples',
			'chessmap',
			'ChessPursuit',
			'chess_game_visualizer',
			'clife-step1',
			'clojure-kit',
			'clojure-starter',
			'conx',
			'ctrlp.vim',
			'data-fixtures',
			'dbal',
			'DebuggingBundle',
			'diem',
			'diem-project',
			'diem-talk',
		];
		$repositories = array_map(function ($name) {
			return ['name' => $name];
		}, $repositories);
		return $repositories;
	}

	/**
	 * @param $repositories
	 * @return array
	 */
	private function generateRequests($repositories): array {
		$requests = [];
		/** @var Repo $repo */
//		$repo = $client->api('repo');
		foreach ($repositories as $rep) {
			echo $rep['name'], BR;
			$request = $this->show($this->username, $rep['name']);
			echo TAB, $request->getUri(), BR;
			//echo TAB, $info['forks'], BR;
			//break;
			$requests[] = $request;
		}
		return $requests;
		//var_dump($request);
//		$response = $this->httpClient->send($request);
//		var_dump($response);
	}

	function requestFulfilled(Response $response, $index) {
		// this is delivered each successful response
		echo $index, TAB, $response->getStatusCode(), BR;
		echo $response->getBody()->getContents(), BR;
	}

}
