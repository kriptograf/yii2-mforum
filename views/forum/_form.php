<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kriptograf\mforum\models\Forum;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\Forum */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="forum-form col-md-5">

   	<?php $form = ActiveForm::begin(); ?>

    <?php 
    	$dataList=ArrayHelper::map(Forum::find()
    		->asArray()
    		->all(),
    		'id', 'title'
      ); 

    	$nullOption = array(NULL => 'No parent');
    	$dataList = ArrayHelper::merge($dataList, $nullOption);
    
    	echo $form->field($model, 'parent_id')
          ->dropDownList(
          	$dataList,
        		['options' =>
             	[
                Yii::$app->getRequest()->getQueryParam('parent_id') => ['selected ' => true]
              ]
            ]
          )
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_locked')
      ->dropDownList(
        array('0' => 'No',
        '1' => 'Yes')
        )
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
