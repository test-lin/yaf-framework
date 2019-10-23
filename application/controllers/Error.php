<?php

use app\core\Xlog as log;
use app\common\exception\Validate as ValidateException;
use app\common\exception\Rpc as RpcException;
use OSS\Core\OssException;
use think\db\exception\DbException;

class ErrorController extends BaseController
{
	public function init()
	{
		parent::init();
	}

    public function errorAction($exception)
    {
        if ($this->configs['project']['debug']) {
            print_r($exception);
        } else {
            try {
                throw $exception;
            } catch (ValidateException $e) {
                $this->validateExceptionHandler($e);
            } catch (RpcException $e) {
                $this->exceptionHandler($e);
            } catch (DbException $e) {
                // 数据库异常
                $this->dbExceptionHandler($e);
            } catch (Exception $e) {
                // 其它异常监控
                $this->exceptionHandler($exception);
            }
        }
    }

    private function exceptionHandler($exception)
    {
        log::error($exception);

        $this->responseFail('系统异常,请反馈处理');
    }

    /**
     * @param Exception $exception
     */
    private function validateExceptionHandler($exception)
    {
        $this->responseFail($exception->getMessage());
    }

    private function dbExceptionHandler($exception)
    {
        log::error($exception);

        $this->responseFail('数据操作异常,请反馈处理');
    }
}
