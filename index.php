<?php

ini_set('display_errors', 1);
error_reporting(-1);

spl_autoload_register(function($class){
	$class = str_replace('\\', '/', $class);

	if (0 === strpos($class, 'TooBasic/Rpc/'))
		require('TooBasic-Rpc/'. substr($class, strlen('TooBasic/Rpc/')) .'.php');
	elseif (0 === strpos($class, 'MaxMind/'))
		require('MaxMind/src/MaxMind/Db/'. substr($class, strlen('MaxMind/Db/')) .'.php');
	else
		require($class .'.php');
});

class BitcoindStatus extends TooBasic\Controller
{
	protected $_config;
	public static $client;
	public static $tpl;

	protected function _construct()
	{
		require('config.php');
		$this->_config = $config;

		self::$tpl = new TooBasic\Template;

		$curl = new TooBasic\Rpc\Transport\Curl;
		$curl->setOption(CURLOPT_USERPWD, $config['rpcUser'].':'.$config['rpcPassword']);
		self::$client = new TooBasic\Rpc\Client\Json('http://'.$config['rpcHost'].'/', $curl);

		self::$tpl->geoIp = new MaxMind\Db\Reader(__DIR__.'/GeoLite2-Country.mmdb');
	}

	protected function _handle(TooBasic\Exception $e)
	{
		if (!headers_sent())
		{
			header('Content-Type: text/plain');
			http_response_code(500);
		}

		echo str_replace(dirname(__DIR__), '.', $e);
	}

	public function getIndex()
	{
		self::$tpl->info = self::$client->getinfo();
		self::$tpl->info->network = self::$client->getnetworkinfo();
		self::$tpl->info->mempool = self::$client->getmempoolinfo();

		$curl = new TooBasic\Rpc\Transport\Curl;
		$network = json_decode($curl->request('GET', 'https://bitnodes.21.co/api/v1/snapshots/'));
		self::$tpl->blockCount = $network->results[0]->latest_height;

		if (isset($this->_config['publicHost']))
			self::$tpl->onlineInfo = json_decode($curl->request('GET', 'https://bitnodes.21.co/api/v1/nodes/'.strtr($this->_config['publicHost'].'/', ':', '-')));
		else
			self::$tpl->onlineInfo = (object)['status' => '<i>unknown</i>'];

		self::$tpl->peers = self::$client->getpeerinfo();
		usort(self::$tpl->peers, function($a, $b){
			return intval(100000*($a->minping - $b->minping));
		});


		print self::$tpl->get('index')->getWrapped();
	}

	public static function binaryPrefix(int $size, $precision = 0, $format = '%.2f %sb')
	{
		$prefixes = ['K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'];
		$prefix = '';

		while ($size >= 1024 && $prefix = array_shift($prefixes))
			$size = round($size/1024, $precision);

		return sprintf($format, $size, $prefix);
	}
}

BitcoindStatus::dispatch('/'. $_SERVER['QUERY_STRING']);