<?php

use yii\helpers\Html;

?>

<div class="post">
  <div class="title">
    <h2><a href=<?= $model->url; ?>><?= Html::encode($model->title); ?></a></h2>
    <span class="glyphicon glyphicon-time" aria-hidden="true"><?= date('Y-m-d H:i:s', $model->create_time) ?></span>&nbsp&nbsp&nbsp&nbsp
    <span class="glyphicon glyphicon-user" aria-hidden="true"><?= Html::encode($model->author->nickname) ?></span>

    <div><?= $model->beginning ?></div>
  </div>

  <div class="nav">
    <span class="glyphicon glyphicon-tag" aria-hidden="true"></span>
    <?= implode(',', $model->tagLinks) ?>

    <br>

    <?= Html::a("评论 ({$model->commentCount})", $model->url . '#comments') ?>&nbsp&nbsp&nbsp&nbsp
    <span>最后修改时间: <?= date('Y-m-d H:i:s', $model->update_time) ?></span>
  </div>
</div>
