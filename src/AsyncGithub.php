<?php

namespace GitGuzzle;

use Github\Api\Repo;
use Github\Client;
use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\MessageFactoryDiscovery;

class AsyncGithub {

	public $username = 'ornicar';

	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @var HttpMethodsClient
	 */
	protected $httpClient;

	function __construct() {
		$this->client = new Client(null, null, 'http://localhost:8081/slawa/guzzle/htdocs/');
		$this->httpClient = $this->client->getHttpClient();
	}

	function render() {
		/** @var \Github\Api\User $user */
		//$user = $this->client->api('user');
		//$repositories = $user->repositories($this->username);
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

		/** @var Repo $repo */
//		$repo = $client->api('repo');
		foreach ($repositories as $rep) {
			echo $rep['name'], BR;
			$request = $this->show($this->username, $rep['name']);
			echo TAB, $request->getUri(), BR;
			//echo TAB, $info['forks'], BR;
		}
		var_dump($request);
		$response = $this->httpClient->sendRequest($request);
		var_dump($response);
	}

	function show($username, $repository) {
		$url = 'sleep.php/repos/'.rawurlencode($username).'/'.rawurlencode($repository);

		$requestFactory = MessageFactoryDiscovery::find();
		$request = $requestFactory->createRequest('get', $url, [], NULL);

		return $request;
	}

}
