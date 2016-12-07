<?php

namespace kriptograf\mforum\controllers;

use Yii;
use kriptograf\mforum\models\Post;
use kriptograf\mforum\models\Thread;
use kriptograf\mforum\models\ThreadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kriptograf\mforum\components\AccessRule;
use yii\data\Pagination;

/**
 * ThreadController implements the CRUD actions for Thread model.
 */
class ThreadController extends Controller
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
     * Displays a single Thread model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $posts = Post::find()
            ->where(['thread_id' => $id]);

        $countPosts = clone $posts;
        $pagination = new Pagination(['totalCount' => $countPosts->count(), 'pageSize' => 10]);
        $posts = $posts->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $modelPost = new Post();
        $modelPost->thread_id = Yii::$app->getRequest()->getQueryParam('id');
        $modelPost->author_id = Yii::$app->user->identity->id;
        $modelPost->editor_id = Yii::$app->user->identity->id;
        if ($modelPost->load(Yii::$app->request->post()) && $modelPost->save()) {
            // send email
                \Yii::$app->mailer->compose('@vendor/kriptograf/yii2-mforum/views/mail/text/newpost', ['content' => $modelPost->content])
                    ->setFrom([\Yii::$app->params['forumEmailSender']])
                    ->setTo(\dektrium\user\models\User::find()
                        ->where([
                            'id' => \kriptograf\mforum\models\Post::find()
                                        ->where(['thread_id' => $modelPost->thread_id])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->one()->author_id
                            ])
                        ->one()->email)
                    ->setSubject('New Post')
                    ->send();

            Controller::refresh();
        }

        $model = $this->findModel($id);
        $model->view_count = $model->view_count + 1;
        $model->save();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'posts' => $posts,
            'modelPost' => $modelPost,
            'pagination' => $pagination
        ]);
    }

    /**
     * Creates a new Thread model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->getRequest()->getQueryParam('forum_id') == NULL)
            return $this->goBack();

        $modelPost = new Post();
        $model = new Thread();

        $model->view_count = 0;
        $model->forum_id = Yii::$app->getRequest()->getQueryParam('forum_id');

        $isLocked = \kriptograf\mforum\models\Forum::find()
            ->where(['id' => $model->forum_id])
            ->one()->is_locked;
        
        if(!$isLocked) {
            if ($model->load(Yii::$app->request->post()) && $model->validate($model) && $model->save()) {

                $modelPost->load(Yii::$app->request->post());
                $modelPost->thread_id = $model->id;
                $modelPost->author_id = Yii::$app->user->identity->id;
                $modelPost->editor_id = Yii::$app->user->identity->id;
                $modelPost->save();

                // send email to admin
                \Yii::$app->mailer->compose('@vendor/kriptograf/yii2-mforum/views/mail/text/newtopic', ['subject' => $model->subject])
                    ->setFrom(\Yii::$app->params['forumEmailSender'])
                    ->setTo(\Yii::$app->params['adminEmail'])
                    ->setSubject('New Topic')
                    ->send();

                    return $this->redirect(['view', 'id' => $model->id]);
            } else {
                    return $this->render('create', [
                        'model' => $model,
                        'modelPost' => $modelPost
                    ]);
            }
        } elseif($isLocked && Yii::$app->user->identity->isAdmin) {
            if ($model->load(Yii::$app->request->post()) && $model->validate($model) && $model->save()) {

                $modelPost->load(Yii::$app->request->post());
                $modelPost->thread_id = $model->id;
                $modelPost->author_id = Yii::$app->user->identity->id;
                $modelPost->editor_id = Yii::$app->user->identity->id;
                $modelPost->save();

                // send email to admin
                \Yii::$app->mailer->compose('@vendor/kriptograf/yii2-mforum/views/mail/text/newtopic', ['subject' => $model->subject])
                    ->setFrom(\Yii::$app->params['forumEmailSender'])
                    ->setTo(\Yii::$app->params['adminEmail'])
                    ->setSubject('New Topic')
                    ->send();

                    return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'modelPost' => $modelPost
                ]);
            }
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        } 
    }

    /**
     * Updates an existing Thread model.
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
     * Deletes an existing Thread model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $forumId = $this->findModel($id)->forum_id;
        $this->findModel($id)->delete();

        return $this->redirect(['forum/view', 'id' => $forumId]);
    }

    /**
     * Finds the Thread model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Thread the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Thread::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
