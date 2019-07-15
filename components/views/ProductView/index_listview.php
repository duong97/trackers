<?php 
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\data\Pagination;
?>

<div class="loggers-form">

    <?php $form = ActiveForm::begin([
	'id' => 'form-create',
        'method' => 'get'
	]); ?>

    <?= $form->field($searchModel, 'name')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php echo ListView::widget([
    'dataProvider' => $dataProvider,
    'pager' => [
        'pagination' => new Pagination([
                            'defaultPageSize' => DEFAULT_PAGE_SIZE,
                        ]),
        'options' => [
            'class'=>'pagination pull-right',
        ]
    ],
    'summaryOptions' => [
        'class' => 'summary text-right'
    ],
    'layout' => "{items}\n<div class='clearfix'></div>{summary}{pager}",
    'itemView' => function ($model, $key, $index, $widget) {
        return $this->render('_item', ['mProduct' => $model]);
    },
]);

