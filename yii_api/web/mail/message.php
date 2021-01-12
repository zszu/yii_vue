<?php



use yii\helpers\Html;

use yii\helpers\Url;

use app\models\Product;

?>

<p>您好，</p>

<p>网站有新的留言提交，请 <a href="<?= Url::to(['/admin/message'], true) ?>" target="_blank">点击这里</a> 查看。</p>

<p></p>

<p>Name: <?= Html::encode($name) ?></p>

<p>E-mail: <?= Html::encode($email) ?></p>

<p>Phone: <?= Html::encode($phone) ?></p>

<?php if ($pid): ?>

<p>Product: <?= Html::a(Product::find()->where(['id' => $pid])->select('title')->scalar(), Url::to(['/site/product-detail', 'id' => $pid], true), ['target' => '_blank'])  ?>  (<?= Html::encode($nums) ?>)</p>

<?php endif; ?>

<p>Content: </p>

<p><?= Html::encode($content) ?></p>

