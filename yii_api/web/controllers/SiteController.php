<?php

namespace app\controllers;

use app\helpers\FileUpload;
use app\models\Link;
use app\models\Page;
use Yii;
use yii\data\Pagination;
use yii\web\Response;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
//组件



//模型
use app\models\Visit;
use app\models\Param;
use \app\common\models\Message;
use \app\common\models\Type;
use \app\common\models\Articles;
class SiteController extends Controller
{
    public $layout = '_header';

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionImages(){
        $upload = new FileUpload();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $filePath = 'data/test/';
        $res = $upload->upload('file' , $filePath);

        return ['url' => $res];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionMessage(){
        $model = new Message();
        if(Yii::$app->request->post() && $model->load(Yii::$app->request->post())){
            if($model->save()){
                return $this->success('提交成功' , ['index','#'=>'page8']);
            }else{
                return $this->error('提交失败' , ['index','#'=>'page8']);
            }
        }
    }

   
    //列表
    private function column($model,$size,$type=''){

        $modelName = 'app\common\models\\'.ucfirst($model);
        $query = $modelName::find()->where(['status' => 1])->orderBy('order_by DESC, id DESC');

        $typeObj = Type::findOne(['english_name'=>$type,'name'=>$model]);
        $typeTitle = Param::getValue('site_title');
        // seo设置
        $keywords = Param::getValue('site_keywords');
        $description = Param::getValue('site_description');
        if($type){
            $query =$query->andFilterWhere(['type'=>$typeObj->id]);
            $typeTitle = $typeObj->seoTitle;
            $keywords = $typeObj->seoKeywords;
            $description = $typeObj->seoDescription;

        }

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => $size,
        ]);
        $this->params['seo_title'] = $typeTitle;
        $this->params['seo_keywords'] = $keywords;
        $this->params['seo_description'] = $description;

        $models = $query->offset(($pages->offset))
            ->limit($pages->limit)
            ->orderBy('order_by DESC, id DESC')
            ->all();


        return $this->render($this->action->id  , [
            'models'=>$models,
            'pages'=>$pages,
            'currentTypeId'=>$typeObj?$typeObj->id:null,
            'currentTypeTitle'=>$typeObj?$typeObj->title:null,
            'typesObj'=>Type::findAll(['status'=>1,'name'=>$model]),
        ]);

    }
    //详情
    private function detail($id,$model){
        $modelName = 'app\common\models\\'.ucfirst($model);
        $model = $modelName::findOne($id);
        if(!$model){
            throw new NotFoundHttpException('资料不存在');
        }

        $model->updateCounters(['views' => 1]);
        $keywords = Param::getValue('site_keywords');
        $summary = Param::getValue('site_description');

        $this->params['seoTrans'] = [
            '{title}' => $model->title,
            '{keywords}'=>trim($model->keywords)?:$keywords,
            '{description}'=>trim($model->summary)?:$summary,
        ];
        $typeObj = Type::findOne(['id'=>$model->type]);
        $prev = $modelName::find()->where(['status' => 1])->andWhere(['<', 'id', $model['id']])->orderBy('id DESC')->limit(1)->asArray()->one();//上一条
        $next = $modelName::find()->where(['status' => 1])->andWhere(['>', 'id', $model['id']])->orderBy('id ASC')->limit(1)->asArray()->one();//
        return $this->render($this->action->id , [
            'model'=>$model,
            'prev'=>$prev,
            'next'=>$next,
            'currentTypeTitle'=>$typeObj?$typeObj->title:null,
            'currentTypeId'=>$typeObj->id ?? null,
            'typeObj'=>$typeObj ?? null,
        ]);

    }


    //新闻
    public function actionNews(){
        $type = Yii::$app->request->get('type');
        return $this->column('articles',10,$type);
    }
    public function actionNewsDetail(){
        $id = Yii::$app->request->get('id');
        return $this->detail($id , 'articles');
    }



    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        $model = Page::findOne(5);
        if(!$model){
            throw new NotFoundHttpException('404');
        }
        return $this->render('about' , [
            'model'=>$model,
        ]);
    }

    //    下载
    public function actionDown($path)
    {

//        if (!$model) {
//            throw new NotFoundHttpException('资料不存在！');
//        }
//        if (!$model['annex']) {
//            return $this->error('资料未上传！');
//        }
        return Yii::$app->response->sendFile(Yii::getAlias('@webroot'. $path));
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new Message();
        $model = $model->loadDefaultValues();

        if(Yii::$app->request->post() && $model->load(Yii::$app->request->post())){
            if($model->save()){
                return $this->success('提交成功' , ['contact']);
            }else{
                return $this->error('提交失败',['contact']);
            }
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }


    public function actionStop()
    {
        return $this->render('stop');
    }
    public function beforeAction($action){
         if (parent::beforeAction($action)) {
             if (Yii::$app->user->isGuest) {
                    // 系统关闭处理
                    if ($action->id != 'stop' && ArrayHelper::getValue($this->params, 'site_status', 1) == 0) {
                        $this->redirect(['/site/stop']);
                    }
                }
                $view = Yii::$app->view;
                $view->params['recommendNews'] = Articles::find()->select('id,title,cover,summary')->where(['status'=>1])->limit(4)->all();

                //seo处理
                $this->view->attachBehavior('seo', \app\components\ZSeoBehavior::className());
                return true;
        } else {
            return false;
        }
    }
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
         Visit::log($action->id);

        return $result;
    }

}
