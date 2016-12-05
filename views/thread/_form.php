<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\Thread */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thread-form col-md-5">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?php if(\Yii::$app->user->identity->isAdmin):?>
    <?= $form->field($model, 'is_locked')
      ->dropDownList(
        array('0' => 'No',
        '1' => 'Yes')
        ) ?>
    <?php else:
        echo $form->field($model, 'is_locked')->hiddenInput(['value' => 0])->label('');
    endif; ?>

    <?= !$model->isNewRecord ? $form->field($model, 'view_count')->textInput(['maxlength' => true]): '' ?>

</div>

<?php if ($model->isNewRecord): ?>
    
    <div class="post-form col-md-10">

    <?= $form->field($modelPost, 'content')->widget('\kartik\markdown\MarkdownEditor', 
    [
        'showExport' => true,
        'encodeLabels' => false,
    ]) ?>

    <?= \nemmo\attachments\components\AttachmentsInput::widget([
        'id' => 'file-input', 
        'model' => $modelPost,
        'options' => [
            'multiple' => true,
        ],
        'pluginOptions' => [  
            'maxFileCount' => 10 
        ]
    ]) ?>

    </div>
<?php endif; ?>

    <div class="form-group col-md-12">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm', 'onclick' => "$('#file-input').fileinput('upload');"]) ?>
    </div>
    <?php ActiveForm::end(); ?>