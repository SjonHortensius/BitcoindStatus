<?php

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
		self::$tpl->blockCount = self::$client->getblockcount();

		if (isset($this->_config['publicHost']))
			self::$tpl->onlineInfo = json_decode((new TooBasic\Rpc\Transport\Curl)->request('GET', 'https://bitnodes.21.co/api/v1/nodes/'.str_replace(':', '-', $this->_config['publicHost'].'/')));
		else
			self::$tpl->onlineInfo = (object)['status' => '<i>unknown</i>'];

#var_dump(self::$tpl->onlineInfo);
		print self::$tpl->get('index')->getWrapped();
	}

	public static function binaryPrefix(int $size, $precision = 0, $format = '%.2f %sb')
	{
		$prefixes = array('K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
		$prefix = NULL;

		while ($size >= 1024 && $prefix = array_shift($prefixes))
			$size = round($size/1024, $precision);

		return sprintf($format, $size, $prefix);
	}
}
