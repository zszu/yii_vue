<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Response;
/**
 * Class BeforeSend
 * @package api\behaviors
 */
class ResponseBehavior extends Behavior
{
    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            'beforeSend' => 'beforeSend',
        ];
    }

    /**
     * 格式化返回
     *
     * @param $event
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeSend($event)
    {
        //不做 格式化 返回
        $arr = ['zu' , 'debug' , 'gii' , 'curl'];
        if (YII_DEBUG && in_array(Yii::$app->controller->module->id , $arr)) {
            return;
        }

        $response = $event->sender;

        $response->data = [
            'code' => $response->statusCode,
            'message' => $response->statusText,
            'data' => $response->data,
        ];

        // 记录日志
         $errData = $response->data;
        // 格式化报错输入格式
        if ($response->statusCode >= 500) {
            $response->data['data'] = YII_DEBUG ? $errData : '内部服务器错误,请联系管理员';
        }


        // 提取系统 300-499 的报错信息
        if ($response->statusCode >= 300 && $response->statusCode <= 499) {
            if (isset($response->data['data']['message']) && isset($response->data['data']['status'])) {
                $response->data['message'] = $response->data['data']['message'];
            }

            if (isset($errData['errorMessage'])) {
                $response->data['message'] = $errData['errorMessage'];
                $response->data['message'] == $response->data['data'] && $response->data['data'] = [];
            }
        }

        // 加入ip黑名单
        // $response->statusCode == 429 && Yii::$app->services->ipBlacklist->create(Yii::$app->request->userIP, '请求频率过高');

        $response->format = Response::FORMAT_JSON;
        $response->statusCode = 200; // 考虑到了某些前端必须返回成功操作，所以这里可以设置为都返回200的状态码
    }


}