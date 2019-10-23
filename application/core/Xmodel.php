<?php
/**
 * 数据层集成
 */
namespace app\core;

use think\Db;
use think\Model;

class Xmodel extends Model
{
    /**
     * 生成排序配置
     *
     * @param array $orderConfig = ['popularity' => 'ASC', 'saleNum' => 'DESC', 'price' => 'ASC'];
     * @param array $orderFields = ['popularity' => 'gg.popularity', 'saleNum' => 'gg.sale_num', 'price' => 'gg.market_price'];
     * @return array
     */
    protected static function orderBy(array $orderConfig, array $orderFields)
    {
        $order = [];
        foreach ($orderConfig as $orderField => $orderBy) {
            if (!array_key_exists($orderField, $orderFields)) {
                continue;
            }

            $orderBy = ($orderBy == 'DESC') ? 'DESC' : 'ASC';
            $order[$orderFields[$orderField]] = $orderBy;
        }

        return $order;
    }

    // 生成单号
    public static function genOrdersSn(int $count = 1)
    {
        $sn = [];
        for ($i = 0; $i < $count; $i ++) {
            $ordersSnSet = Db::query('call order_seq()');
            $sn[] = $ordersSnSet[0][0]['order_sn'];
        }

        return $sn;
    }

    public static function getFieldByKey(string $field, $value, string $key = 'id')
    {
        return self::where($key, $value)->value($field);
    }

    public static function getFieldsByKey(string $fields, $value, string $key = 'id')
    {
        return self::field($fields)->where($key, $value)->find();
    }

    public static function getFieldsByKeyOrFail(string $fields, $value, string $key = 'id')
    {
        return self::field($fields)->where($key, $value)->findOrFail();
    }

    public static function getsFieldsByKey(string $fields, $value, string $key = 'id')
    {
        return self::field($fields)->where($key, $value)->select();
    }

    public static function getsFieldsByKeyOrFail(string $fields, $value, string $key = 'id')
    {
        return self::field($fields)->where($key, $value)->selectOrFail();
    }

    public static function createItem(array $data)
    {
        return self::insertGetId($data);
    }

    public static function deleteItem($value, string $key = 'id')
    {
        return self::where($key, $value)->delete();
    }

    public static function countByPk($value, string $key = 'id')
    {
        return self::where($key, $value)->count();
    }
}
