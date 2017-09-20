<?php
namespace Swoole;

class Application
{
	public $objects;
	public $config;
	public static $instance;
	
	private function __construct()
	{
		$this->config = new Config;
        $this->config->setPath(APPLICATION_PATH . "/Configs");
	}
	
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
	
	public function __get($key)
	{
		return $this->loadModule($key);
	}

	private function loadModule($module)
	{
		if (empty($this->objects[$module])) {
            $factory_file = APPLICATION_PATH . '/Factory/' . $module . '.php';
			//组件不存在，抛出异常
			if (!is_file($factory_file)) {
				throw new NotFound("module [$module] not found.");
			}
			$object = require $factory_file;
            $this->objects[$module] = $object;
        }
        return $this->objects[$module];
	}
	
	public function run()
	{
		$server = new \Swoole\Thrift\Server($this->config['service']);
		$serv = new \swoole_server($this->config['swoole']['host'], $this->config['swoole']['port']);
		$serv->on('workerStart', [$server, 'onStart']);
		$serv->on('receive', [$server, 'onReceive']);
		$serv->set($this->config['swoole']['setting']);
		$serv->start();
	}
}