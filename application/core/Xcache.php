<?php
/**
 * 集成缓存
 * Cache::set('namess', '111111');
 * Cache::store('storage')->set('namess', '222222');
 *
 * Cache::get('namess');
 * Cache::store('storage')->get('namess');
 */
namespace app\core;

use \think\Cache;

class Xcache
{
	private static $config;
	private static $instance;

	public static function setConfig($config)
	{
		self::$config = $config;
	}

	public static function store($name = 'default')
	{
		if(!isset(self::$instance[$name]))
		{
			self::$instance[$name] = Cache::connect(self::$config[$name]);
		}

		return self::$instance[$name];
	}

	public static function __callStatic($method, $args)
    {
        return forward_static_call_array([self::store(), $method], $args);
    }
}
