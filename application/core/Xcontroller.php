<?php
/**
 * 过滤一些用户输入的信息
 */
namespace app\core;

use Yaf\Controller_Abstract as YafControllerAbstract;
use Yaf\Registry as YafRegistry;

class Xcontroller extends YafControllerAbstract
{
	protected $configs;

	public function init()
	{
		$this->configs = YafRegistry::get('config')->toArray();
	}

	public function setLayout($layout)
	{
		$this->getResPonse()->layout = $layout;
	}

	public function setLayoutVal($key, $val)
	{
		if(!isset($this->layout))
		{
			$this->layout = YafRegistry::get('layout');
		}
		$this->layout->$key = $val;
	}

	/**
	 * @desc 返回请求的类型
	 *
	 * @return string GET/POST/HEAD/PUT/CLI
	 */
	protected function getMethod()
	{
		return $this->getRequest()->getMethod();
	}

	/**
	 * @desc 是否是ajax请求
	 *
	 * @return boolean true/false
	 */
	protected function isAjax()
	{
		return $this->getRequest()->isXmlHttpRequest();
	}

	/**
	 * @desc 是否是post请求
	 *
	 * @return boolean true/false
	 */
	protected function isPost()
	{
		return $this->getRequest()->isPost();
	}

    /**
	 * @desc 可以获取地址和？后面部分的参数数据
	 * eg: 可以获取 getParam、getQuery的数据
     * @param $name
     * @param null $default_value
     * @param bool $isFilter
     * @return mixed
     */
	protected function get($name, $default_value = null, $isFilter = true)
	{
		$data = $this->getRequest()->get($name, $default_value);

		if($isFilter)
		{
			return $this->xssClean($data);
		}else{
			return $data;
		}
	}

	// 获取所有的请求参数 移除路由变量
	protected function gets($isFilter = true)
	{
		$data = $this->getRequest()->getQuery();
		array_shift($data);
		foreach($data as &$val)
		{
			if($isFilter)
			{
				$val = $this->xssClean($val);
			}
		}

		return $data;
	}

	protected function getPost($name, $default_value = null, $isFilter = true)
	{
		$data = $this->getRequest()->getPost($name, $default_value);

		if($isFilter){
			return $this->xssClean($data);
		}else{
			return $data;
		}
	}

	protected function getPosts($isFilter = true)
	{
		$data = $this->getRequest()->getPost();

		foreach($data as &$val)
		{
			if($isFilter)
			{
				$val = $this->xssClean($val);
			}
		}

		return $data;
	}

	/**
     * 数据安全过滤
     * @param $data
     * @return mixed
     */
    protected function xssClean($data)
    {
        if(is_array($data))
		{
            return filter_var_array($data, FILTER_SANITIZE_STRING);
        }else{
            return filter_var($data, FILTER_SANITIZE_STRING);
        }
    }
}
