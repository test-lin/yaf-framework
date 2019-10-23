<?php
/**
 * 验证集成
 */
namespace app\core;

use \think\Validate;
use app\common\exception\Validate as ValidateException;

class Xvalidate extends Validate
{
    /**
     * 检测所有客户端发来的参数是否符合验证类规则
     *
     * @param $params
     * @param bool $batch
     * @return bool
     * @throws ValidateException
     */
    public function goCheck($params, $batch = false)
    {
        if (!$this->batch($batch)->check($params)){
            throw new ValidateException($this->getError());
        }

        return true;
    }

    /**
     * @param array $arrays 传入变量数组
     * @param array $fields 字段
     * @param boolean $without 是否排除字段
     * @return array 按照规则key过滤后的变量数组
     * @throws ValidateException
     */
    public function getDataByRule($arrays, $fields = [], $without = false)
    {
        if (array_key_exists('id', $arrays)) {
            unset($arrays['id']);
        }

        if (array_key_exists('created', $arrays) | array_key_exists('updated', $arrays)) {
            // 不允许恶意覆盖指定的字段
            throw new ValidateException('包含有非法的参数');
        }

        $newArray = [];
        $keys = $fields;
        if (empty($fields)) {
            $keys = array_keys($this->rule);
        }

        foreach ($keys as $key) {
            if ($without && in_array($key, $keys)) {
                continue;
            }

            if (!isset($arrays[$key])) {
                continue;
            }

            $newArray[$key] = $arrays[$key];

        }

        return $newArray;
    }

	protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }

    // 没有排除0的情况
    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) {
            return $field . '不允许为空';
        } else {
            return true;
        }
    }

    // 没有排除0的情况
    protected function isString($value, $rule = '', $data = '', $field = '')
    {
        if (is_string($value)) {
            return true;
        } else {
            return $field . '必须是字符类型';
        }
    }

    protected function isNonexistent($value, $rule = '', $data = '', $field = '')
    {
        if (isset($value) && ! empty($value)) {
            return $field . '不可以传值';
        } else {
            return true;
        }
    }

    //手机号的验证规则
    protected function isMobile($value)
    {
        $rule = '^1[3456789]\d{9}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
