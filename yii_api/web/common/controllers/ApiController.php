<?php
namespace app\common\controllers;

use yii\rest\ActiveController;
use yii\filters\RateLimiter;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use Yii;
use yii\base\Behavior;
use yii\web\Response;
use app\helpers\ResultDataHelper;
/**
 * restful api 底层基控制器
 * class ApiController
 * @package app\common\controllers
 */
class ApiController extends ActiveController
{
    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];
     /**
     * 行为验证
     *
     * @return array
     */
    public function behaviors()
    {

        $behaviors = parent::behaviors();
       // 跨域支持
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],

                //客户端 和 服务 端 交互时 使用 token true 允许客户端携带验证信息
                'Access-Control-Allow-Credentials' => false,
            ],
        ];

        // 移除行为的权限验证的优先级
        unset($behaviors['authenticator']);

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
//            'authMethods' => [
//                HttpBearerAuth::class,
//                HttpHeaderAuth::class,
//                [
//                    'class' => QueryParamAuth::class,
//                    'tokenParam' => 'access-token',
//                ],
//            ],
            // 不进行认证判断方法
            'optional' => $this->authOptional,
        ];
        /**
         * 请求速率控制
         *
         * limit部分，速度的设置是在common\models\common\RateLimit::getRateLimit($request, $action)
         * 当速率限制被激活，默认情况下每个响应将包含以下HTTP头发送 目前的速率限制信息：
         * X-Rate-Limit-Limit: 同一个时间段所允许的请求的最大数目;
         * X-Rate-Limit-Remaining: 在当前时间段内剩余的请求的数量;
         * X-Rate-Limit-Reset: 为了得到最大请求数所等待的秒数。
         * enableRateLimitHeaders：false: 不开启限制 true：开启限制
         */
        $behaviors['rateLimiter'] = [
            'class' => RateLimiter::class,
            'enableRateLimitHeaders' => true,
        ];

        return $behaviors;
    }
    /**
     * {@inheritdoc}
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
            'view' => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['POST' , 'PUT', 'PATCH', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS','POST'],
        ];
    }
    public function api($code = 404, $message = '未知错误', $data = []){
        return ResultDataHelper::api($code , $message, $data);
    }
    public function json($code = 404, $message = '未知错误', $data = []){
   
        return ResultDataHelper::json($code , $message, $data);
    }

}