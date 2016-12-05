<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\Thread */

$this->title = 'Create Topic';
$this->params['breadcrumbs'][] = ['label' => 'Threads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelPost' => $modelPost
    ]) ?>

</div>
