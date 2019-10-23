<?php
/**
 * 地区库信息
 */
class RegionModel extends BaseModel
{
    protected $name = 'region';

	public static function getNameById($id)
    {
        return static::getFieldByKey('region_name', $id, 'region_id');
    }
}
