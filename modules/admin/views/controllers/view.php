<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Controllers */

$this->title = $model->display_name;
$this->params['breadcrumbs'][] = ['label' => 'Controllers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$aAction = empty($model->actions) ? [] : json_decode($model->actions, true);
$order   = 1;
?>
<div class="controllers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $model->can('update') ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'controller_name',
            'display_name',
            'module_name',
            'actions:ntext',
        ],
    ]) ?>
    <div class="list-action">
        <table class="table table-striped" style="width: 100%">
            <thead>
                <th>STT</th>
                <th>Tên</th>
                <th>Diễn giải</th>
            </thead>
            <tbody>
                <?php foreach ($aAction as $data): ?>
                <tr>
                    <td><?php echo $order++; ?></td>
                    <td><?php echo $data['key']; ?></td>
                    <td>
                        <?php echo $data['value']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
</div>
