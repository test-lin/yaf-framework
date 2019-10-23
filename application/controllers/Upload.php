<?php

use app\common\exception\Validate as ValidateException;
use OSS\Core\OssException;

class UploadController extends BaseController
{
    /**
     * @var string[] 视频文件类型限制
     */
    protected $videoExt = ['mp4'];

    /**
     * @var string[] 图片文件类型限制
     */
    protected $imageExt = ['jpg','png','gif','jpeg'];

    /**
     * @var string[] 其他文件类型限制
     */
    protected $otherExt = ['pem'];

    /**
     * @throws ValidateException
     * @throws OssException
     */
    public function indexAction()
    {
        // 接收参数
        $folder = $this->getPost('folder', '');
        $container = isset($_FILES['container']) ? $_FILES['container']:'';
        $getInfo = $this->getPost('get_info', '');

        // 验证上传的合法性
        $fileExt = array_merge($this->videoExt, $this->imageExt, $this->otherExt);
        $this->checkUpload($folder, $container, $fileExt);

        // 获取资源
        $data = $this->getFileData($folder, $container);

        $this->response($data, $getInfo);
    }

    private function response($info, $getInfo = false)
    {
        $data = array(
            'file_id' => $info['file_id'],
            'name' => $info['name'],
            'size' => $info['size'],
            'path' => $info['path'],
            'url' => $this->getFilePath($info['path'])
        );

        if($getInfo) {
            $spec = $this->getImageInfo($data['url']);
            $data['spec'] = isset($spec['width']) ? "{$spec['width']} x {$spec['height']}" : '';
        }

        $this->responseSuccess('文件上传成功', $data);
    }

    /**
     * 获取资源
     *
     * @param $folder
     * @param $file
     * @return array
     * @throws OssException
     */
    private function getFileData($folder, $file)
    {
        $data = [];
        $temp = $file['tmp_name'];

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $path = $folder.'/'.date('Ym').'/'.$this->getUniqueId().'.'.$ext;

        $configs = $this->configs['oss'];
        if ($this->uploadOss($configs, $path, $temp)) {
            $path = "/{$path}";

            $data = [
                'path' => $path,
                'size' => $file['size'],
                'name' => $file['name'],
            ];
        }

        return $data;
    }

    /**
     * 上传OSS操作
     *
     * @param $configs
     * @param $path
     * @param $temp
     * @return bool
     * @throws OssException
     */
    private function uploadOss($configs, $path, $temp)
    {
        $oss = new Oss($configs);

        if($oss->uploadObject($configs['default']['bucket'], $temp, $path)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证上传文件
     *
     * @param $folder
     * @param $file
     * @param string[] $validateExt
     * @throws ValidateException
     */
    private function checkUpload($folder, $file, $validateExt = [])
    {
        if(empty($folder)) {
            throw new ValidateException('目录无效');
        }

        if(!isset($file['error'])) {
            throw new ValidateException('文件无效');
        }
        switch($file['error'])
        {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
                throw new ValidateException('上传的文件超过了php.ini中upload_max_filesize选项限制的值');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                throw new ValidateException('上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值');
                break;
            case UPLOAD_ERR_PARTIAL:
                throw new ValidateException('文件只有部分被上传');
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new ValidateException('没有文件被上传');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                throw new ValidateException('找不到临时文件夹');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                throw new ValidateException('文件写入失败');
                break;
        }

        if ($validateExt) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

            if (in_array($ext, $validateExt) === false) {
                throw new ValidateException('文件类型不合法');
            }
        }
    }

    private function getUniqueId()
    {
        return session_create_id();
    }
}