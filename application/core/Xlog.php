<?php
/**
 * 集成缓存
 * Log::warning
 * Log::debug
 * Log::error
 * Log::info
 * Log::notice
 * Log::sql
 */
namespace app\core;

use \think\Log;

class Xlog
{
	private static $config;
	private static $instance;

	public static function setConfig($config)
	{
		self::$config = $config;
	}

	// 记录warning信息
	public static function warning($message, array $context = [])
	{
		self::store()->warning($message, $context);

		self::write();
	}

	// 调试日志
	public static function debug($message, array $context = [])
	{
		self::store()->debug($message, $context);

		self::write();
	}

	// 错误日志
	public static function error($message, array $context = [])
	{
		self::store()->warning($message, $context);

		self::write();
	}

	// 记录notice信息
	public static function notice($message, array $context = [])
	{
		self::store()->notice($message, $context);

		self::write();
	}

	// 记录一般信息
	public static function info($message, array $context = [])
	{
		self::store()->info($message, $context);

		self::write();
	}

	// 记录sql信息
	public static function sql($message, array $context = [])
	{
		self::store()->sql($message, $context);

		self::write();
	}

	private static function store()
	{
		if(!isset(self::$instance))
		{
			self::$instance = (new Log())->init(self::$config);
		}

		return self::$instance;
	}

	private static function write()
	{
		self::store()->save();
	}
}
