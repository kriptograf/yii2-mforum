<?php

namespace kriptograf\mforum\controllers;

use Yii;
use kriptograf\mforum\models\Forum;
use kriptograf\mforum\models\Thread;
use kriptograf\mforum\models\ForumSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kriptograf\mforum\components\AccessRule;
use yii\data\Pagination;
/**
 * ForumController implements the CRUD actions for Forum model.
 */
class ForumController extends Controller
{
    public function behaviors()
    {
        return [
           'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                //'only' => ['create', 'update', 'index', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['view', 'index'],
                        'allow' => true,
                        'roles' => ['?', '@', 'admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Forum models.
     * @return mixed
     */
    public function actionIndex()
    {   
        $forums = Forum::find()
            ->where(['parent_id' => NULL])
            ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'forums' => $forums,
        ]);
    }


    /**
     * Displays a single Forum model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $forums = Forum::find()
            ->where(['parent_id' => $id])
            ->all();

        $threads = Thread::find()
            ->where(['forum_id' => $id]);
        $countQuery = clone $threads;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 5]);
        $threads = $threads->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'forums' => $forums,
            'threads' => $threads,
            'pagination' => $pagination
        ]);
    }

    /**
     * Creates a new Forum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Forum();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->parent_id == NULL)
                return $this->redirect('index');
            else
                return $this->redirect(['view', 'id' => $model->parent_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Forum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Forum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $parentId = $this->findModel($id)->parent_id;
        $this->findModel($id)->delete();

        if($parentId == NULL)
            return $this->redirect(['index']);
        else
            return $this->redirect(['view', 'id' => $parentId]);
            
    }

    /**
     * Finds the Forum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Forum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Forum::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
