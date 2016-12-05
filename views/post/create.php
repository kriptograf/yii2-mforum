<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\Post */

if(Yii::$app->controller->module->id == 'forum' && Yii::$app->controller->id == 'thread' && Yii::$app->controller->action->id == 'view')
	$this->title = '';
else {
	$this->title = 'Create Post';
	$this->params['breadcrumbs'][] = ['label' => 'Forums', 'url' => ['forum/index']];

	$this->params['breadcrumbs'][] = [
	     'label' => \kriptograf\mforum\models\Forum::find()
	    		->joinWith(['threads'])
	    		->where(['thread.id' => Yii::$app->getRequest()->getQueryParam('thread_id')])->one()->title,
	     'url' => ['forum/view', 'id' => \kriptograf\mforum\models\Forum::find()
	    		->joinWith(['threads'])
	    		->where(['thread.id' => Yii::$app->getRequest()->getQueryParam('thread_id')])->one()->id,
	    	]];
	$this->params['breadcrumbs'][] = [
		'label' => \kriptograf\mforum\models\Thread::find()
			->where(['id' => Yii::$app->getRequest()->getQueryParam('thread_id')])
			->one()->subject, 
		'url' => ['thread/view', 'id' => \kriptograf\mforum\models\Thread::find()
			->where(['id' => Yii::$app->getRequest()->getQueryParam('thread_id')])
			->one()->id]
	];	
	$this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
