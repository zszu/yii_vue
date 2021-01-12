<?php
namespace app\helpers;


/**
 * Class UploadDrive
 * @package app\helpers
 * @desc 上传驱动类
 */
class UploadDriver
{
    //驱动类型 local 本地
    public $driver;
    /**
     * 上传组件
     *
     * @var Filesystem
     */
    protected $filesystem;
    /**
     * Filesystem constructor.
     * @param $drive
     * @throws \Exception
     */
    public function __construct($driver)
    {
        $this->driver = $driver;

    }
    public function getFileSystem(){
        return $this->filesystem;
    }
}