<?php
namespace app\controllers\traits;
use Yii;

Trait CmsTrait{
    private $articleView = '/cms/';
    // 新闻管理  Model  -- News
    public function actionNews(){
        $modelClass = 'app\common\models\Articles';
        return $this->index($modelClass,'',$this->articleView);
    }
    public function actionNewsEdit(){
        $id = Yii::$app->request->get('id');
        $modelClass = 'app\common\models\Articles';
        $action = 'admin/news';
        return $this->imgEdit($modelClass,$id,$action,$this->articleView);
    }
    public function actionNewsDelete(){
         $id = Yii::$app->request->get('id');
         $modelClass = 'app\common\models\Articles';
         $action = 'admin/news';
         return $this->delete($modelClass,$id,$action);
    }
     // 产品管理  Model  -- Product
    public function actionProduct(){
        $modelClass = 'app\common\models\Goods';
        return $this->index($modelClass,'',$this->articleView);
    }
    public function actionProductEdit(){
        $id = Yii::$app->request->get('id');
        $modelClass = 'app\common\models\Goods';
        $action = 'admin/product';
         return $this->imgEdit($modelClass,$id,$action,$this->articleView);
    }
    public function actionProductDelete(){
         $id = Yii::$app->request->get('id');
         $modelClass = 'app\common\models\Goods';
         $action = 'admin/product';
         return $this->delete($modelClass,$id,$action);
    }
    //轮播图管理  model  --  Slide
    public function actionSlide(){
        $modelClass = 'app\common\models\Slide';
        return $this->index($modelClass,'',$this->articleView);
    }
    public function actionSlideEdit(){
        $id = Yii::$app->request->get('id');
        $modelClass = 'app\common\models\Slide';
        $action = 'admin/slide';
        return $this->imgEdit($modelClass,$id,$action,$this->articleView);
    }
    public function actionSlideDelete(){
         $id = Yii::$app->request->get('id');
         $modelClass = 'app\common\models\Slide';
         $action = 'admin/slide';
         return $this->delete($modelClass,$id,$action);
    }
    //友链管理  model  --  Link
    public function actionLink(){
        $modelClass = 'app\common\models\Link';
        return $this->index($modelClass,'',$this->articleView);
    }
    public function actionLinkEdit(){
        $id = Yii::$app->request->get('id');
        $modelClass = 'app\common\models\Link';
        $action = 'admin/link';
        return $this->Edit($modelClass,$id,$action,$this->articleView);
    }
    public function actionLinkDelete(){
        $id = Yii::$app->request->get('id');
        $modelClass = 'app\common\models\Link';
        $action = 'admin/link';
        return $this->delete($modelClass,$id,$action);
    }



} 

?>