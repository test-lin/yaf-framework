<?php

namespace app\common;

/**
 * 项目约束格式
 *
 * @package app\common
 */
trait Project
{
    //执行成功输出的json
    protected function responseSuccess($msg, $data = [])
    {
        $this->callbackAjax($msg, 200, $data);
    }

    //执行失败输出的json
    protected function responseFail($msg, $data = [])
    {
        $this->callbackAjax($msg, 400, $data);
    }

    //其他类型输出格式
    protected function responseOther($msg, $data = [], $code = 200)
    {
        $this->callbackAjax($msg, $code, $data);
    }

    protected function callbackAjax($msg,$code = 200, $data = array())
    {
        if($data)
        {
            $result = $data;
        }else{
            $result = get_empty_obj();
        }

        $json = array(
            'result'=> $result,
            'msg'   => $msg,
            'status'=> (int)$code
        );

        self::callbackJson($json);
    }

    protected function callbackJson($arr = array())
    {
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options:nosniff;');
        echo json_encode($arr);
        exit;
    }

    /**
     * 获取页面参数
     *
     * @param null|int $page 当前页
     * @param null|int $pageLimit 数据条数
     * @return array
     */
    protected function getPageParam($page = null, $pageLimit = null)
    {
        if (is_null($page)) {
            $page = $this->getPost('page', 1);
        }

        if (is_null($pageLimit)) {
            $pageLimit = $this->getPost('pageSize', 10);
        }

        $page = ($page < 1) ? 1 : $page;
        $pageLimit = ($pageLimit < 1 || 100 < $pageLimit) ? 100 : $pageLimit;

        return [
            'page' => $page,
            'pageSize' => $pageLimit
        ];
    }

    /**
     * 金额记录
     * @param mixed $price 金额（元）
     * @return string 金额（分）
     */
    protected function priceEncode($price)
    {
        return bcmul($price, PRICE_FORM);
    }
    /**
     * 金额显示
     * @param mixed $price 金额（角）
     * @return string 金额（元）
     */
    protected function priceDecode($price)
    {
        return bcdiv($price, PRICE_FORM, 2);
    }

    /**
     * 百分比记录
     * @param mixed $rate 百分比（显示值）
     * @return string 百分比（数据库值）
     */
    protected function rateEncode($rate)
    {
        return bcmul($rate, RATE_FORM);
    }
    /**
     * 百分比显示
     * @param mixed $rate 百分比（数据库值）
     * @return string 百分比（显示值）
     */
    protected function rateDecode($rate)
    {
        return bcdiv($rate, RATE_FORM,2);
    }

    /**
     * 获取加密数据
     *
     * @param $password
     * @return bool|string
     */
    protected function getPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 校验密码
     *
     * @param $password
     * @param $hashPwd
     * @return bool
     */
    protected function checkPassword($password, $hashPwd)
    {
        return password_verify($password, $hashPwd);
    }

}