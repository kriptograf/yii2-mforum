<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\forum\models\ForumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Forums';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    		<?php
    		if(Yii::$app->user->identity->isAdmin)
        		echo Html::a('Create Forum', ['create'], ['class' => 'btn btn-success btn-sm']) ;
     	?>
    </p>

    <?= $this->render('_forums', ['forums' => $forums]); ?>
</div>
