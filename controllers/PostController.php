<?php

namespace kriptograf\mforum\controllers;

use Yii;
use kriptograf\mforum\models\Post;
use kriptograf\mforum\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kriptograf\mforum\components\AccessRule;
use yii\helpers\Url;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public function behaviors()
    {
        return [
           'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@', 'admin'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['?', '@', 'admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->getRequest()->getQueryParam('thread_id') == NULL)
            return $this->goBack();

        $model = new Post();
        $model->thread_id = Yii::$app->getRequest()->getQueryParam('thread_id');
        $model->author_id = Yii::$app->user->identity->id;
        $model->editor_id = Yii::$app->user->identity->id;

        $isLocked = \kriptograf\mforum\models\Thread::find()
            ->joinWith('posts')
            ->where(['thread_id' => $model->thread_id])
            ->one()->is_locked;

        if(!$isLocked) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // send email to author
                \Yii::$app->mailer->compose('@vendor/kriptograf/yii2-mforum/views/mail/text/newpost', ['content' => $model->content])
                    ->setFrom([\Yii::$app->params['forumEmailSender']])
                    ->setTo(\dektrium\user\models\User::find()
                        ->where([
                            'id' => \kriptograf\mforum\models\Post::find()
                                        ->where(['thread_id' => $model->thread_id])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->one()->author_id
                            ])
                        ->one()->email)
                    ->setSubject('New Post')
                    ->send();

                return $this->redirect(['thread/view', 'id' => $model->thread_id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } elseif($isLocked && Yii::$app->user->identity->isAdmin) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['thread/view', 'id' => $model->thread_id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->editor_id = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['thread/view','id' => $model->thread_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $threadId = $this->findModel($id)->thread_id;
        $this->findModel($id)->delete();

        return $this->redirect(['thread/view','id' => $threadId]);

    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
