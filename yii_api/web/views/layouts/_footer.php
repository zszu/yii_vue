<?php
use \yii\helpers\Url;
$this->beginContent('@app/views/layouts/_base.php');
$action = Yii::$app->controller->action->id;
?>


<?= $content;?>

底部

<?php $this->endContent();?>