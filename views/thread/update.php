<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\Thread */

$this->title = 'Update Topic: ' . ' ' . $model->subject;
$this->params['breadcrumbs'][] = ['label' => 'Forums', 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => \kriptograf\mforum\models\Forum::find()->where(['id' => $model->forum_id])->one()->title,
    'url' => ['forum/view','id' => \kriptograf\mforum\models\Forum::find()->where(['id' => $model->forum_id])->one()->id]
    ];
$this->params['breadcrumbs'][] = ['label' => $model->subject, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="thread-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
