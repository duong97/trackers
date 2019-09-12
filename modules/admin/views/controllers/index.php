<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý chức năng';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="controllers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Tạo mới chức năng', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'display_name',
            'module_name',
            'controller_name',
            'actions:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}{delete}{grantPrivilege}',
                'buttons'=>[
                    'grantPrivilege'=>function ($url, $model, $key) {
                        $url = Url::to(['/admin/privileges/grant', 'ctlID'=>$model->id]);
                        return Html::a('<span class="glyphicon glyphicon-user"></span>',
                            $url, ['title' => 'Phân quyền', 'target'=>'_blank']);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
