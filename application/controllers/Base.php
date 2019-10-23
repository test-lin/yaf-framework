<?php

use app\common\Project;
use Yaf\Dispatcher as YafDispatcher;
use OSS\Core\OssException;

class BaseController extends app\core\Xcontroller
{
    use Project;

    protected $platformId;
    protected $userId;

	public function init()
	{
		YafDispatcher::getInstance()->autoRender(FALSE);

		parent::init();
	}

	protected function getFilePath($path, $domain = '')
    {
        $imgDomain = isset($this->configs['project']['ossdomain']) ? $this->configs['project']['ossdomain'] : '';

        if($domain) {
            $imgDomain = $domain;
        }
        if (empty($imgDomain)) {
            throw new OssException('访问域名有误');
        }

        $path = $path ? $path : '/common/lose.png';

        return $imgDomain.'/'.trim($path,'/');
    }

    protected function getImageInfo($url)
    {
        $href = $url.'?x-oss-process=image/info';

        $timeout = 10;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $href);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        $result = curl_exec($curl);
        if(curl_errno($curl)) {
            return false;
        }
        curl_close($curl);

        if($result) {
            $result = preg_replace('/(ACD.*)"/', '"', $result);
        }

        $infos = json_decode($result, true);
        $info = array();
        if(isset($infos['ImageHeight']) && isset($infos['ImageHeight']['value'])) {
            $info = array(
                'height' => $infos['ImageHeight']['value'],
                'width' => $infos['ImageWidth']['value'],
                'size' => $infos['FileSize']['value'],
            );
        }

        return $info;
    }

    protected function getYarParam($platformId, $userId)
    {
        $platformId = $platformId ? $platformId : $this->platformId;
        $userId = $userId ? $userId : $this->userId;

        return [
            'platform_id' => $platformId,
            'user_id' => $userId,
            'config' => $this->configs['yar'],
        ];
    }
}
