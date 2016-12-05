<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\User;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form col-md-12">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'content')->widget('\kartik\markdown\MarkdownEditor', 
    [
        'showExport' => true,
        'encodeLabels' => false,
    ]) ?>

    <?= \nemmo\attachments\components\AttachmentsInput::widget([
        'id' => 'file-input', 
        'model' => $model,
        'options' => [
            'multiple' => true,
        ],
        'pluginOptions' => [  
            'maxFileCount' => 10 
        ]
    ]) ?>

    <div class="form-group">
        <?php if(!Yii::$app->user->isGuest):?>
        <div>
            <?= Html::submitButton($model->isNewRecord ? 'Reply' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm', 'onclick' => "$('#file-input').fileinput('upload');"]) ?>
        </div>
        <?php else:?>
        <div>
            <?= Html::a('Please log in to reply', ['/user/login'], ['class' => 'btn btn-success btn-sm']) ?>
            <?php Yii::$app->user->returnUrl = Yii::$app->request->url; ?>
        </div>
        <?php endif;?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
