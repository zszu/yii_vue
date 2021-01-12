<?php

namespace app\modules\api\v1\controllers;

use app\modules\api\v1\models\UserObj;
use app\common\controllers\OnAuthController;
use app\helpers\ResultDataHelper;
use app\models\form\ChangePwdForm;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * User controller for the `Module` module
 */
class UserController extends OnAuthController
{
    public  $modelClass = UserObj::class;
    public $authOptional = ['index' , 'edit','reset-pwd'];

    /**
     * @return array|mixed
     * @throws \yii\base\Exception
     * @desc 重置密码
     */
    public function actionResetPwd(){
        $model = new ChangePwdForm();
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate()) {
            return ResultDataHelper::api(422, $model->getFirstErrors());
        }

        $user = $model->getUser();
        $user->setPassword($model->password);
        $user->generateAccessToken();

        if (!$user->save()) {
            return ResultDataHelper::api(422, $user->getFirstErrors());
        }
        return [ 'userInfo' => $user];

    }
    public function actionIndex(){
        return $this->modelClass::getUsersRoles();
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->attributes = Yii::$app->request->post();

        $roles = Yii::$app->request->post()['role'] ?? '';
        $model->role = $roles;
        return $this->modelClass::updateUser($model);
    }

    /**
     * @param $id
     * @return mixed|\yii\db\ActiveRecord
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdateAvatar($id){
        $model = $this->findModel($id);
        $model->attributes = Yii::$app->request->post();
        if(!$model->save()){
            return ResultDataHelper::api(422, $model->getFirstErrors());
        }else{
            return $model;
        }
    }

    /**
     * 添加
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    private function actionRegister()
    {
        $model = new RegisterForm();
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate()) {
            return ResultDataHelper::api(422, $this->getError($model));
        }

        $member = new Member();
        $member->attributes = ArrayHelper::toArray($model);
        $member->password_hash = Yii::$app->security->generatePasswordHash($model->password);
        if (!$member->save()) {
            return ResultDataHelper::api(422, $this->getError($member));
        }

        return Yii::$app->services->apiAccessToken->getAccessToken($member, $model->group);
    }
    /**
     * 用戶角色
     */
    public function actionUserRole(){
        $query = $this->modelClass::find();
        $query = $query->andWhere(['<>' , 'username' , 'admin'])->andWhere(['group'=>User::GROUP_WORKER])->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $this->pageSize,'pageSizeParam' => false],
            'sort'=> false,
        ]);
        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     *  设置用户权限
     */
    public function actionPermissionEdit($id){
        $user = User::find()
            ->andWhere(['id' => $id])
            ->one();
        if (!$user) {
            return $this->message("资料不存在！", $this->redirect(['index']));
        }

        $model = new PermissionForm(['userId' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->message("权限设置成功", $this->redirect(['permission']));
            }
        }

        $permissionArr = Yii::$app->authManager->getPermissionsByUser($id);
        $model->permissionArr = ArrayHelper::getColumn($permissionArr, 'name');

        $roleArr = Yii::$app->authManager->getRolesByUser($id);
        $model->roleArr = ArrayHelper::getColumn($roleArr, 'name');

        return $this->render('permission-edit', [
            'user' => $user,
            'model' => $model,
        ]);
    }
    //

}
