<?php
namespace app\common\controllers;
use yii\helpers\Url;
/**
 * 公共控制器
 * author zsz
 */
class CommonController  extends \yii\web\Controller
{
    public $data = [];
    public $params = [];
    public $links = [];
	public function msg($message, $url = '', $params = [])
    {
        $data = [
            'title' => '系统提示',
            'type' => 'info',
            'time' => 3000,
            'message' => $message,
            'url' => $url,
            'view' => '//layouts/msg',
        ];
        foreach ($params as $k => $v) {
            if (isset($data[$k])) {
                $data[$k] = $v;
            }
        }

        return $this->render($data['view'], [
            'data' => $data,
        ]);
    }

    public function success($message, $url = null, $params = [])
    {
        if (!$url) {
            $url = Url::current();
        }
        $params['type'] = 'success';
        return $this->msg($message, $url, $params);
    }

    public function error($message, $url = '', $params = [])
    {
        $params['type'] = 'danger';
        return $this->msg($message, $url, $params);
    }
}

?>