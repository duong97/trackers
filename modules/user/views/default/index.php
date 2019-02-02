<div>
    <?php // echo \app\components\ProductViewWidget::widget() ?>
    <?php echo "name: ".!empty($aData['name']) ? $aData['name'] : "" ?><br>
    <?php echo "price: ".isset($aData['price']) ? $aData['price'] : "" ?><br>
    <?php if(!empty($aData['image'])){ ?>
    <?php foreach ($aData['image'] as $img_url) { ?>
    <img src="<?php echo $img_url['normal'] ?>" style="height: 100px"><br>
    <?php }} ?>
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div>
