<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model kriptograf\mforum\models\Forum */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Forums', 'url' => ['index']];
if($model->parent != NULL) 
    $this->params['breadcrumbs'][] = [
        'label' => \kriptograf\mforum\models\Forum::find()->where(['id' => $model->parent_id])->one()->title,
        'url' => ['forum/view','id' => \kriptograf\mforum\models\Forum::find()->where(['id' => $model->parent_id])->one()->id]
    ];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Yii::$app->user->identity->isAdmin):
            echo Html::a('Create Subforum', ['create', 'parent_id' => $model->id], ['class' => 'btn btn-success btn-sm']);
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']);
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
            endif;
        ?>
    </p>

    <?php 
    if (!empty($forums) ) 
        echo $this->render('_forums', ['forums' => $forums]);
    // display pagination
    echo yii\widgets\LinkPager::widget([
        'pagination' => $pagination,
    ]);
    ?>

    <?php if(!$model->is_locked) {
            if(!Yii::$app->user->isGuest) {?>
            <div class="pull-right">
                <?= Html::a('Start new topic', ['thread/create', 'forum_id' => Yii::$app->getRequest()->getQueryParam('id')], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
            <?php } else {?>
            <div class="pull-right">
                <?= Html::a('Please log in to post a topic', ['/user/login'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
            <?php }
        } elseif($model->is_locked && Yii::$app->user->identity->isAdmin) {?>
            <div class="pull-right">
                <?= Html::a('Start new topic', ['thread/create', 'forum_id' => Yii::$app->getRequest()->getQueryParam('id')], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
    <?php } else {?>
            <div class="pull-right">
                <?= Html::a('Forum locked', ['thread/create', 'forum_id' => Yii::$app->getRequest()->getQueryParam('id')], ['class' => 'btn btn-success btn-sm disabled']) ?>
            </div>
    <?php } ?>  
    
    <?= $this->render('_threads', [
        'threads' => $threads
        ]); ?>
</div>
