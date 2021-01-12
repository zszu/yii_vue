<?php
namespace app\helpers;
/**
 * 上穿辅助类
 * @package app\helpers
 */
class UploadHelper
{
    /**
     * 上传类型
     */
    protected $type;
    /**
     * 上传文件基础信息
     *
     * @var array
     */
    protected $baseInfo = [
        'name' => '',
        'width' => '',
        'height' => '',
        'size' => 0,
        'extension' => 'jpg',
        'url' => '',
        'merge' => false,
        'guid' => '',
        'type' => 'image/jpeg',
    ];

    /**
     * UploadHelper constructor.
     * @param array $config
     * @param $type 上传类型
     */
    public function __construct(array $config , $type)
    {
        //设置文件类型
        $this->type = $type;

    }
    //验证文件
    public function verifyFile(){

    }
    //保存文件
    public function save(){

    }
}