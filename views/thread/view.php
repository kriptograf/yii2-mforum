<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\Thread */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => 'Forums', 'url' => ['forum/index']];

$this->params['breadcrumbs'][] = [
    'label' => \kriptograf\mforum\models\Forum::find()->where(['id' => $model->forum_id])->one()->title,
    'url' => ['forum/view','id' => \kriptograf\mforum\models\Forum::find()->where(['id' => $model->forum_id])->one()->id]
    ];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Yii::$app->user->identity->isAdmin):
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

        <?php echo yii\widgets\LinkPager::widget([
            'pagination' => $pagination,
        ]);?>

        <?php 
        if(!$model->is_locked) {
            if(!Yii::$app->user->isGuest) {?>
            <div class="pull-right">
                <?= Html::a('Reply to this topic', ['post/create', 'thread_id' => $model->id], ['class' => 'btn btn-success btn-sm','onclick' => "$('#file-input').fileinput('upload');"]) ?>
            </div>
            <?php } else {?>
            <div class="pull-right">
                <?= Html::a('Please log in to reply', ['/user/login'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
            <?php }
        } elseif($model->is_locked && Yii::$app->user->identity->isAdmin) {?>
            <div class="pull-right">
                <?= Html::a('Topic locked', ['post/create', 'thread_id' => $model->id], ['class' => 'btn btn-success btn-sm ','onclick' => "$('#file-input').fileinput('upload');"]) ?>
            </div>
        <?php } else {?>
            <div class="pull-right">
                <?= Html::a('Topic locked', ['post/create', 'thread_id' => $model->id], ['class' => 'btn btn-success btn-sm disabled']) ?>
            </div>
        <?php } ?>    
    </p>

    <?= $this->render('_posts', [
        'posts' => $posts,
    ]); ?>

    <?php if($model->is_locked && Yii::$app->user->identity->isAdmin) {
        echo $this->render('@vendor/ivan/yii2-simpleforum/views/post/create', [
            'model' => $modelPost,
        ]);
    } elseif (!$model->is_locked && !Yii::$app->user->isGuest) {
        echo $this->render('@vendor/ivan/yii2-simpleforum/views/post/create', [
            'model' => $modelPost,
        ]);
    } ?>
</div>
