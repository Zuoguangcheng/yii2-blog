<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Posts';
?>
<div class="container">
  <div class="row">
    <div class="col-md-9">
      <ol class="breadcrumb">
        <li><a href="<?= Yii::$app->homeUrl ?>">首页</a></li>
        <li>文章列表</a></li>
      </ol>
      <?= \yii\widgets\ListView::widget([
        'id' => 'postList',
        'dataProvider' => $dataProvider,
        'itemView' => '_listitem', // 子视图
        'layout' => '{items} {pager}',
        'pager' => [
          'maxButtonCount' => 10,
          'nextPageLabel' => Yii::t('app', '下一页'),
          'prevPageLabel' => Yii::t('app', '上一页'),
        ]
      ]) ?>
    </div>
    <div class="col-md-3">
      <div class="searchbox">
        <ul class="list-group">
          <li class="list-group-item">
            <span class="glyphicon glyphicon-search"></span>查找文章
          </li>
          <li class="list-group-item">
            <form class="form-inline" action="index.php?r=post/index" id="w0" method="get">
              <div class="form-group">
                <input type="text" class="form-control" name="PostSearch[title]" id="w0input" placeholder="按标题搜索">
              </div>
              <button type="submit" class="btn btn-default">搜索</button>
            </form>
          </li>
        </ul>
      </div>

      <div class="tagcloudbox">
        <ul class="list-group">
          <li class="list-group-item">
            <span class="glyphicon glyphicon-tag"></span>标签云
          </li>
          <li class="list-group-item">
            <?= \frontend\components\TagsCloudWidget::widget(['tags' => $tags]) ?>
          </li>
        </ul>
      </div>

      <div class="commentbox">
        <ul class="list-group">
          <li class="list-group-item">
            <span class="glyphicon glyphicon-check"></span>最新回复
          </li>
          <li class="list-group-item">
            <?= \frontend\components\RctReplyWidget::widget(['recentComments' => $recentComments]) ?>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
