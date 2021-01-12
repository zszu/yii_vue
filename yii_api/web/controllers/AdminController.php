<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;


use yii\data\ActiveDataProvider;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
//组件
use app\components\Helper;

//控制器

//模型
use app\models\form\LoginForm;
use app\models\form\ChangePwdForm;
use app\models\Visit;
use app\common\models\Type;
use app\common\models\Message;
//trait类
use app\controllers\traits\ParamTrait;
use app\controllers\traits\CmsTrait;
use app\controllers\traits\SiteMapTrait;

class AdminController extends Controller
{
   //加载trait类
   use ParamTrait;
   use CmsTrait;
   use SiteMapTrait;
   public $layout = 'admin';
    /**
     * Displays homepage.
     *
     * @return string
     */
   public function actionIndex()
    {
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $endDate = date('Y-m-d');
        $pvData = Visit::getPVByRange($startDate, $endDate, false);
        $uvData = Visit::getUVByRange($startDate, $endDate, false);
        return $this->render('index', [
            'dateArr' => json_encode(array_keys($pvData)),
            'pvArr' => json_encode(array_values($pvData)),
            'uvArr' => json_encode(array_values($uvData)),
            'countArr' => [
                'pv' => array_sum($pvData),
                'uv' => array_sum($uvData),
            ],
            'uvData'=>$uvData,
        ]);
    }
    //访问统计
    public function actionStatistic()
    {
        $OneDayAccessArr = Visit::getOneDayAccess();
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $endDate = date('Y-m-d');
        $pvData = Visit::getPVByRange($startDate, $endDate, false);
        $uvData = Visit::getUVByRange($startDate, $endDate, false);

        return $this->render('statistic', [
            'dateArr' => Json::encode(array_keys($pvData)),
            'pvArr' => Json::encode(array_values($pvData)),
            'uvArr' => Json::encode(array_values($uvData)),
            'countArr' => [
                'pv' => array_sum($pvData),
                'uv' => array_sum($uvData),
            ],
            'OneDayAccessArr'=>$OneDayAccessArr,

        ]);
    }

    
    //留言管理    model  --  Message
    public function actionMessage(){
        $modelClass = 'app\common\models\Message';
        return $this->index($modelClass);
    }
    public function actionApprove(){
        $id = Yii::$app->request->get('id');
        $modelObj = Message::findOne($id);
        if(!$modelObj){
            return $this->error('没此记录' , 'message');
        }
        $modelObj->updateAttributes(['status' => 1]);
        return $this->success('审核成功' , ['message']);
    }
    public function actionMessageEdit(){
        $id = Yii::$app->request->get('id');
        $modelClass = 'app\common\models\Message';
        $action = 'admin/message';
        return $this->Edit($modelClass,$id,$action);
    }
    public function actionMessageDelete(){
         $id = Yii::$app->request->get('id');
         $modelClass = 'app\common\models\Message';
         $action = 'admin/message';
         return $this->delete($modelClass,$id,$action);
    }
    //分类管理    model  --  Type

    public function actionType()
    {
        $request = Yii::$app->request;
        $name = $request->get('name', '');
        $dataProvider = new ActiveDataProvider([
            'query' => Type::find()
                ->where(['name' =>$name])
                ->orderBy('order_by desc,id'),
        ]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'name'  => $name
        ]);
    }
    public function actionTypeEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', '');
        $name = $request->get('name', '');
        $model = new Type();
        if(!empty($id)){
             $model = Type::findOne($id);
        }
        if (Yii::$app->request->post()) {
            if($model->load(Yii::$app->request->post())&&$model->save()){
                return $this->success('编辑成功！', ['admin/type', 'name' => $name]);
           }
        }
        return $this->render('type-edit',['model'=>$model]);

    }
    public function actionTypeDelete()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $name = $request->get('name', '');
        $model = Type::findOne($id);
        if (!$model) {
            return $this->error('资料不存在');
        }

        if ($model->delete()) {
            return $this->redirect(['admin/type' , 'name'=>$name]);
        } else {
            return $this->error('删除失败:' . implode('|', $model->firstErrors));
        }
    }




    //关于我们     model --Page
    public function actionPage()
    {
        $modelClass = 'app\models\Page';
        return $this->index($modelClass);
    }

   public function actionPageEdit(){
        $modelClass = 'app\models\Page';
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $action = 'admin/page';
        return $this->Edit($modelClass,$id,$action);
    }

    public function actionPageDelete($id)
    {
        $modelClass = 'app\models\Page';
        $action = 'admin/page';
        return $this->delete($modelClass, $id, $action);
    }
    //删除图片
    public function actionPictureDelete(){
        $request = Yii::$app->request;
        $id = $request->get('id');
        $table_name = $request->get('table_name');
        if(!$id)
        {
          return $this->error('未添加记录');
        }
        $field = $request->get('field');
      
        $model = 'app\common\models\\'.$table_name;
        $res = Helper::ImageDelete($id,$table_name,$field,$model);
        if($res){
            return $this->redirect($request->referrer);
             // return $this->success('删除成功',$request->referrer);
        }
        return $this->error('没有该图片');
    }
    //删除多图
    public function actionPhotoDelete($id,$k,$table)
    {
        $request = Yii::$app->request;
        $table = strtolower($table);
        $modelClass = 'app\common\models\\'.ucfirst($table);

        $model = $modelClass::findOne(['id' => $id]);
        
        $action = 'admin/'.$table.'-edit';
        if (!$model) {
            return $this->error('资料不存在');
        }
        if (!isset($model->photoArr[$k])) {
            return $this->error('图片不存在');
        }
        Helper::unlink($model->photoArr[$k]);
        unset($model->photoArr[$k]);
        if ($model->save()) {
             return $this->redirect($request->referrer);
            // return $this->success('删除成功', [$action, 'id' => $id]);
        } else {
            return $this->error('删除失败:' . implode('|', $model->firstErrors));
        }
    }

    //修改密码
    public function actionResetPwd()
    {
        $model = new ChangePwdForm();
        $model->user = Yii::$app->user->identity;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->doSave()) {
                return $this->success('密码修改成功！', ['logout']);
            } else {
                Yii::error($model->errors);
                return $this->error('密码修改失败！');
            }
        }
        return $this->render('reset-pwd', [
            'model' => $model,
        ]);
    }
    //找回密码
    public function actionRefundPassword()
    {
        $model = new RefundPasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->doSave()) {
                return $this->success('密码修改成功！', ['/admin/index']);
            } else {
                Yii::error($model->errors);
                return $this->error('密码修改失败！');
            }
        }
        return $this->render('refund-password', [
            'model' => $model,
        ]);
    }

    //登录
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/admin/index']);
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    //退出
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/admin/index']);
    }
   //清理缓存
    public function actionClearCache()
    {

        if (Yii::$app->cache->flush()) {
            return $this->success('缓存清理成功！', Yii::$app->request->referrer);
        } else {
            return $this->error('缓存清理失败！', Yii::$app->request->referrer);
        }
    }

    public function actions()
    {
        return [
            'upload' => ['class' => 'app\extensions\ZKindEditor\UploadAction'],
            'manage' => ['class' => 'app\extensions\ZKindEditor\ManageAction'],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'padding' => 0,
                'width' => 110,
                'height' => 38,
                'minLength' => 4,
                'maxLength' => 4,
                'foreColor' => 0x009DDA,
            ],
        ];
    }
   
   

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'user' => 'user',
                'except' => ['login', 'upload', 'captcha'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }
    public  function beforeAction($action)
    {
        Yii::$app->language = ArrayHelper::getValue($_COOKIE, 'language', 'zh-CN');
        if ($action->id == 'upload' && Yii::$app->user->isGuest) {
            Yii::$app->request->enableCsrfValidation = false;
        }

       Yii::$container->set('yii\grid\GridView', [
             'layout' => "\n{items}\n<div class=\"pages\">{summary}".'条记录{pager}</div>',
             'summary'=>'共有{totalCount}',
        ]);
        Yii::$container->set('yii\widgets\ListView', [
             'layout' => "\n{items}\n<div class=\"pages\">{summary}".'条记录{pager}</div>',
             'summary'=>'共有{totalCount}',
        ]);

        Yii::$container->set('yii\grid\DataColumn', [
            'filterInputOptions' => ['class' => 'form-control input-sm', 'id' => null],
        ]);

        Yii::$container->set('yii\widgets\ActiveField', [
            'options' => ['tag' => 'tr'],
            'template' => "<th>{label}</th>\n<td>{input}\n{hint}\n{error}</td>"
        ]);

        return parent::beforeAction($action);
    }


}
