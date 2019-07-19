<?php 
$this->params['breadcrumbs'] = [
    ['label'=>'Blog', 'url'=>['/site/blog']],
    ['label'=>$model->title],
];
?>
<div class="container">
    <h1><?= $model->title; ?></h1>
    <?php if( !empty($model->getThumbnailUrl()) ): ?>
    <img src="<?= $model->getThumbnailUrl() ?>" style="max-width: 100%;">
    <?php endif; ?>
    <div>
        <?= $model->content; ?>
    </div>
</div>