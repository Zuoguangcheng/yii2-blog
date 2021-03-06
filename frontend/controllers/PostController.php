<?php

namespace frontend\controllers;

use common\models\Comment;
use common\models\Tag;
use common\models\User;
use Yii;
use common\models\Post;
use common\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
  public $added = 0;

  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['POST'],
        ],
      ],
      // 页面缓存
      'pageCache' => [
        'class' => 'yii\filters\PageCache',
        'only' => ['index'], // 指定缓存的页面
        'duration' => 600,
        'variations' => [
          // 参数变化重新生成缓存
          Yii::$app->request->get('page'),
          Yii::$app->request->get('PostSearch'),
        ],
        'dependency' => [
          'class' => 'yii\caching\DbDependency',
          'sql' => 'select count(id) from post',
        ],
      ],
    ];
  }

  /**
   * Lists all Post models.
   * @return mixed
   */
  public function actionIndex()
  {

    $tags = Tag::findTagWeights();
    $recentComments = Comment::findRecentComment();

    $searchModel = new PostSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
      'searchModel' => $searchModel,
      'dataProvider' => $dataProvider,
      'tags' => $tags,
      'recentComments' => $recentComments,
    ]);
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
    $model = new Post();

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
    } else {
      return $this->render('create', [
        'model' => $model,
      ]);
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

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
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
    $this->findModel($id)->delete();

    return $this->redirect(['index']);
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

  public function actionDetail($id)
  {
    $model = $this->findModel($id);
    $tags = Tag::findTagWeights();
    $recentComments = Comment::findRecentComment();

    $userMe = User::findOne(Yii::$app->user->id);
    $commentModel = new Comment();
    $commentModel->email = $userMe->email;
    $commentModel->userid = $userMe->id;


    Yii::info($commentModel->load(Yii::$app->request->post()), 'info');
    // 评论提交 处理评论
    if ($commentModel->load(Yii::$app->request->post())) {

      if (!Yii::$app->user->can('commentAuditor')) {
        throw new ForbiddenHttpException('对不起， 你没有进行该操作的权限');
      }

      $commentModel->status = 1;
      $commentModel->post_id = $id;
      $commentModel->email = $userMe->email;
      $commentModel->userid = $userMe->id;

      Yii::info($commentModel->status, 'info');
      Yii::info($commentModel->post_id, 'info');
      Yii::info($commentModel->userid, 'info');
      Yii::info($commentModel->email, 'info');
      Yii::info($commentModel->content, 'info');

      if ($commentModel->save()) {
        $this->added = 1;
      }
    }

    // 传递输入给视图
    return $this->render('detail', [
      'model' => $model,
      'tags' => $tags,
      'recentComments' => $recentComments,
      'commentModel' => $commentModel,
      'added' => $this->added,
    ]);

  }
}
