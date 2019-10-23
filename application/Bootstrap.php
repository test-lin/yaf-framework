<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf\Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */

use Yaf\Application as YafApplication;
use app\core\Xcache;
use app\core\Xlog;
use think\Db;

class Bootstrap extends Yaf\Bootstrap_Abstract
{
    private $config;

    public function _initConfig()
    {
		//把配置保存起来
		$this->config = YafApplication::app()->getConfig();
		Yaf\Registry::set('config', $this->config);
	}

    public function _initFunction()
    {
        require APPLICATION_PATH.'/application/common/func.php';
    }

    // 初始化数据库
    public function _initDefaultDbAdapter()
    {
        Db::setConfig($this->config->database->default->toArray());
    }

    // 初始化缓存
    public function _initCacheAdapter()
    {
        Xcache::setConfig($this->config->cache->toArray());
        class_alias('\App\Core\Xcache', 'cache');
    }

    // 初始化日志
    public function _initLogAdapter()
    {
        Xlog::setConfig($this->config->log->toArray());
        class_alias('\App\Core\Xlog', 'log');
    }

	public function _initRoute(Yaf\Dispatcher $dispatcher)
	{
		//在这里注册自己的路由协议,默认使用简单路由
	}

	public function _initView(Yaf\Dispatcher $dispatcher)
	{
		//在这里注册自己的view控制器，例如smarty,firekylin
	}
}
