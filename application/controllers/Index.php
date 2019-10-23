<?php

use app\api\yar\OrdersPackApi;

class IndexController extends BaseController
{
	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
	    $this->responseSuccess('Welcome');
	}
}
