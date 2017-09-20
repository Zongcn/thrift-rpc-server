<?php
namespace Swoole;

class Config extends \ArrayObject
{
    protected $config;
    protected $config_path = array();

	public function setPath($path)
	{
		$this->config_path[] = $path;
	}
	
	public function offsetGet($index)
	{
		if (!isset($this->config[$index])) {
			$this->load($index);
		}
		return isset($this->config[$index]) ? $this->config[$index] : false;
	}
	
	public function offsetSet($index, $newval)
    {
        $this->config[$index] = $newval;
    }
	
    public function offsetUnset($index)
    {
        unset($this->config[$index]);
    }
	
    public function offsetExists($index)
    {
        if (!isset($this->config[$index])) {
            $this->load($index);
        }
        return isset($this->config[$index]);
    }
	
	protected function load($index)
	{
		foreach($this->config_path AS $path) {
			$filename = $path . '/' . $index . '.php';
            if (is_file($filename)) {
				$retData = include $filename;
				$this->config[$index] = $retData;
			}
		}
	}
}