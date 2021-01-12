<?php
namespace app\controllers\traits;

use app\models\Seo;
use Yii;
use yii\web\UploadedFile;
use yii\helpers\Url;

use app\components\ZUpload;
use app\components\Helper;
use app\models\Param;

Trait  ParamTrait{

	  //系统参数管理   model  -- Param
    protected function processParams($condition,$param_type='')
    {


        $query = Param::find()->where($condition);
        $query = $query->andwhere(['status' => 1]);

        $models = $query->orderBy('order_by, name')->all();
        $params = array();
        foreach ($models as $model) {
            $params[$model->id] = $model;
        }
        $updated = false;

        //修改
        if (isset($_POST['Params']) && is_array($_POST['Params'])) {
            $i = 1;
            foreach($_POST['Params'] as $k=>$v) {
                Param::findOne($k)->updateAttributes(['value'=>$v, 'order_by'=>$i*10]);
                $params[$k]->value = $v;
                $updated = true;
                $i++;
            }
            Yii::$app->cache->delete('cache.params');
        }

         //文件上传
        if (isset($_FILES['Params']['name']) && is_array($_FILES['Params']['name'])) {
            foreach($_FILES['Params']['name'] as $k => $v) {
                $upload = new ZUpload(UploadedFile::getInstanceByName("Params[$k]"), 'param');
                if($upload->save()) {
                    $v = $upload->get_url();
                    //删除旧文件
                    if($params[$k]->value) {
                        Helper::unlink($params[$k]->value);
                    }

                    Param::findOne($k)->updateAttributes(['value'=>$v]);
                    $params[$k]->value = $v;
                    $updated = true;
                }
            }
            Yii::$app->cache->delete('cache.params');
        }

        // 图片删除
        if (isset($_POST['Params_Del']) && is_array($_POST['Params_Del'])) {
            foreach($_POST['Params_Del'] as $k=>$v) {
                Helper::unlink($params[$k]->value);
                Param::findOne($k)->updateAttributes(['value' => '']);
                $params[$k]->value = '';
                $updated = true;
            }
        }

        if ($updated) {
            return $this->success('保存成功！');
        }

        Url::remember();

        return $this->render('params', ['params'=>$params,'param_type'=>$param_type]);
    }

    //站点设置
    public function actionSiteParam()
    {
        return $this->processParams(['type' => Param::TYPE_SITE]);
    }
    //邮箱设置
    public function actionEmailParam()
    {
        return $this->processParams(['type' => Param::TYPE_MAIL]);
    }
    //系统设置
    public function actionSystemParam()
    {
        return $this->processParams(['type' => Param::TYPE_SYSTEM]);
    }

    //公司设置
    public function actionCompanyParam()
    {
        return $this->processParams(['type' => Param::TYPE_COMPANY]);
    }
    //seo设置
    public function actionSeo()
    {
        $models = Seo::find()->where(['status' => 1])->orderBy('order_by DESC, id')->all();

        if (Yii::$app->request->isPost && $seoPost = Yii::$app->request->post('Seo')) {
            $seos = [];
            foreach ($models as $model) {
                $seos[$model['action']] = $model;
            }

            $msg = [];
            foreach($seoPost as $k=>$v) {
                if (isset($seos[$k]) && $seos[$k]->load($v, '')) {
                    if ($seos[$k]->save()) {
                        $msg[] = 'SEO ' . $seos[$k]->name . ' 保存成功！';
                    } else {
                        $msg[] = 'SEO ' . $seos[$k]->name . ' 保存失败！';
                    }
                }
            }

            return $this->success(implode('<br>', $msg));
        }

        return $this->render('seo', [
            'models' => $models
        ]);
    }
}

?>