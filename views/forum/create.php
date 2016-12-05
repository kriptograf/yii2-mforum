<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\Forum */

$this->title = 'Create Forum';
$this->params['breadcrumbs'][] = ['label' => 'Forums', 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => \kriptograf\mforum\models\Forum::find()->where(['id' => Yii::$app->getRequest()->getQueryParam('parent_id')])->one()->title,
    'url' => ['forum/view','id' => \kriptograf\mforum\models\Forum::find()->where(['id' => Yii::$app->getRequest()->getQueryParam('parent_id')])->one()->id]
    ];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
