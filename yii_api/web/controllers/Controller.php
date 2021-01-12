<?php
namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\controllers\CommonController;

use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\Area;

class Controller extends CommonController
{
    const CACHE_KEY_PARAMS = 'cache.params';
    // const CACHE_KEY_LINKS = 'cache.links';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'param' => [
                'class' => '\app\components\behavior\ParamBehavior',
                'cache_name' => self::CACHE_KEY_PARAMS,
            ],
            //  'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'delete' => ['POST'],
            //     ],
            // ],
        ]);
    }

   

     protected function find($modelClass)
     {
         return $modelClass::find()
                           ->orderBy('order_by DESC,id DESC');
     }

    protected function index($modelClass,$sort = [ ],$view='')
    {
      
        $query = $modelClass::find();
        // $count = $query->count();
        if(!$sort) {
            $query = $query->orderBy('order_by DESC,id DESC');
        }else {
            $query = $query->where($sort)->orderBy('order_by DESC,id DESC');
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort'=> false,  //关闭筛选
        ]);
        return $this->render($view.$this->action->id, [
            'dataProvider' => $dataProvider,
            // 'count'=>$count,
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     * by zsz 2019/08/25
     */
    protected function findModel($modelClass, $id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = $modelClass::findOne($id))))
        {
            $model = new $modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }

    protected function delete($modelClass, $id, $action)
    {
        $model = $modelClass::findOne($id);
        if (!$model) {
            return $this->error('资料不存在');
        }
       
        if ($model->delete()) {
            return $this->redirect([$action]);
            // return $this->success('删除成功', [$action]);
        } else {
            return $this->error('删除失败:' . implode('|', $model->firstErrors));
        }
    }

    protected function edit($modelClass, $id, $action,$view='')
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);

        $model = $this->findModel($modelClass,$id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                    return $this->success('编辑成功！', [$action]);
                } /*else {
                    // Yii::error($model->getErrors());
                    // var_dump($model->getErrors());die;
                    return $this->error('编辑失败！');
                }*/
        }
        return $this->render($view.$this->action->id, [
            'model' => $model, 
        ]);
    }



    protected function imgEdit($modelClass, $id, $action,$view='')
    {
        
      
        $model = $this->findModel($modelClass,$id);
        if ($model->load(Yii::$app->request->post())) {
            $model->coverFile = UploadedFile::getInstance($model, 'coverFile');
            $model->qrCodeFile = UploadedFile::getInstance($model, 'qrCodeFile');
            $model->coversFile = UploadedFile::getInstances($model, 'coversFile');
     
            if ($model->save()) {
                    return $this->success('编辑成功！', [$action]);
                } 
                //     else {
                //     var_dump($model->errors);
                //     Yii::error($model->getErrors());
                //     return $this->error('编辑失败！');
                // }
        }
        
        return $this->render($view.$this->action->id, [
            'model' => $model, 
        ]);
    }
}
?>
