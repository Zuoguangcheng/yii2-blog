<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评论管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <? /*= Html::a('创建评论', ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'id',
                'contentOptions' => ['width' => '30px']
            ],
//            'content:ntext',

            [
                'attribute' => 'content',
                'value' => 'beginning'
                /* 'value' => function ($model) {
                     $tempStr = strip_tags($model->content);
                     $tempLen = mb_strlen($tempStr);
                     return mb_substr($tempStr, 0, 20, 'utf-8') . (($tempLen > 20) ? '...' : '');
                 }*/
            ],
//            'status',
            [
                'attribute' => 'status',
                'value' => 'status0.name',
                'filter' => \common\models\Commentstatus::find()
                    ->select(['name', 'id'])
                    ->orderBy('position')
                    ->indexBy('id')
                    ->column(),
                'contentOptions' => function ($model) {
                    return ($model->status == 1) ? ['class' => 'bg-danger'] : [];
                }

            ],
//            'create_time:datetime',
            [
                'attribute' => create_time,
                'format' => ['date', 'php:m-d H:i'],
            ],
//            'userid',
            [
                'attribute' => 'user.username',
                'label' => '作者',
                'value' => 'user.username',
            ],
            // 'email:email',
            // 'url:url',
            // 'post_id',
            'post.title',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {approve}',
                'buttons' => [
                    'approve' => function ($url, $model, $key) {
                        $option = [
                            'title' => Yii::t('yii', '审核'),
                            'aria-label' => Yii::t('yii', '审核'),
                            'data-confirm' => Yii::t('yii', '你确定通过这条记录吗'),
                            'data-method' => 'post',
                            'data-pjax' => 0,
                        ];

                        return Html::a('<span class="glyphicon glyphicon-check"></span>', $url, $option);
                    }
                ]
            ]
        ],
    ]); ?>
</div>


