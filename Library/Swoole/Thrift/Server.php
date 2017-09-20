<?php
namespace Swoole\Thrift;
use Thrift;

class Server
{
	/**
     * Thrift processor
     * @var object 
     */
    protected $processor = null;
	
	protected $services;
	
	public function __construct($services)
	{
		$this->services = $services;
	}
	/**
	 * Server启动在主进程的主线程回调此函数
	 */
	public function onStart()
	{
		
	}
	
	/**
	 * 接收到数据时回调此函数
	 */
	public function onReceive(\swoole_server $swoole_server, $fd, $reactor_id, $data)
	{
		$processor = new Thrift\TMultiplexedProcessor();
		foreach($this->services AS $key=>$service) {
			$processor->registerProcessor(
				$key,
				new $service['processor_class'](new $service['handler_class']())
			);
		}
		
        $socket = new Socket();
        $socket->setHandle($fd);
        $socket->buffer = $data;
        $socket->server = $swoole_server;
        $protocol = new Thrift\Protocol\TBinaryProtocol($socket, false, false);
        try {
            $protocol->fname = "none";
            $processor->process($protocol, $protocol);
        } catch (\Exception $e) {
            echo 'CODE：' . $e->getCode() . ' MESSAGE：' . $e->getMessage() . "\n" . $e->getTraceAsString();
        }
	}
}