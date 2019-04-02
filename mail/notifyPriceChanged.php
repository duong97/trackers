<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

<table  valign="top" style="width:100%;max-width:480px" width="100%" 
        cellspacing="0" cellpadding="0" border="0" align="center">
    <tbody>
        <tr>
            <td style="word-break:normal;border-collapse:collapse;font-family:Comic Sans MS;font-size:12px;line-height:18px;color:#555555"
                valign="top" align="left">
                <center>
                    <div>
                        <table style="border:none;margin:0px;border-collapse:collapse;padding:0px;width:100%;height:50px"
                               width="100%" height="50" cellspacing="0" cellpadding="0">
                            <tbody style="border:none;margin:0px;padding:0px" valign="middle">
                                <tr style="border:none;margin:0px;padding:0px;height:20px" 
                                    valign="middle" height="20">
                                    <td colspan="3" style="border:none;margin:0px;padding:0px;height:20px" 
                                        valign="middle" height="20"></td>
                                </tr>
                                <tr style="border:none;margin:0px;padding:0px" valign="middle">
                                    <td style="border:none;margin:0px;padding:0px;width:6.25%" 
                                        width="6.25%" valign="middle"></td>
                                    <td style="border:none;margin:0px;padding:0px;text-align:center;" valign="middle">
                                        <a style="border:none;margin:0px;padding:0px;font-size:44px;font-weight:bold;text-decoration: none;color:#48b748;font-family:Comic Sans MS" 
                                           href="<?= Url::base(true) ?>" align="center">
                                            <?= \app\helpers\Constants::website_name ?>
                                        </a>
                                    </td>
                                    <td style="border:none;margin:0px;padding:0px;width:6.25%" 
                                        width="6.25%" valign="middle"></td>
                                </tr>
                                <tr style="border:none;margin:0px;padding:0px;height:20px" 
                                         valign="middle" height="20">
                                    <td colspan="3" style="border:none;margin:0px;padding:0px;height:20px" 
                                        valign="middle" height="20"></td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="border:none;margin:0px;border-collapse:collapse;padding:0px;width:100%" 
                               width="100%" cellspacing="0" cellpadding="0">
                            <tbody style="border:none;margin:0px;padding:0px" valign="middle">
                                <tr style="border:none;margin:0px;padding:0px;height:28px" 
                                    valign="middle" height="28">
                                    <td colspan="3" style="border:none;margin:0px;padding:0px;height:28px" 
                                        valign="middle" height="28"></td>
                                </tr>
                                <tr style="border:none;margin:0px;padding:0px" valign="middle">
                                    <td style="border:none;margin:0px;padding:0px;width:6.25%" 
                                        width="6.25%" valign="middle"></td>
                                    <td style="border:none;margin:0px;padding:0px" valign="middle">
                                        <h1 style="border:none;margin:0px;padding:0px;text-decoration:none;color:rgb(85,85,85);font-size:32px;font-weight:bold;line-height:36px;letter-spacing:-0.04em;text-align:center" align="center">
                                            <?= Yii::t('app', 'Notification') ?>.</h1>
                                        <h2 style="border:none;margin:0px;padding:7px 0px 0px;font-family:Comic Sans MS;font-weight:200;text-decoration:none;color:rgb(97,100,103);font-size:17px;line-height:23px;text-align:center" align="center">
                                            <?= Yii::t('app', 'The price of the product you are tracking has been changed') ?>.</h2>
                                    </td>
                                    <td style="border:none;margin:0px;padding:0px;width:6.25%" 
                                        width="6.25%" valign="middle"></td>
                                </tr>
                                <tr style="border:none;margin:0px;padding:0px;height:100px;" valign="middle">
                                    <td style="border:none;margin:0px;padding:0px;width:6.25%" 
                                        width="6.25%" valign="middle"></td>
                                    <td style="border:none;margin:0px;padding:0px" valign="middle">
                                        
                                    </td>
                                    <td style="border:none;margin:0px;padding:0px;width:6.25%" 
                                        width="6.25%" valign="middle"></td>
                                </tr>
                                <tr style="border:none;margin:0px;padding:0px;height:16px" 
                                    valign="middle" height="16">
                                    <td colspan="3" style="border:none;margin:0px;padding:0px;height:16px" 
                                        valign="middle" height="16"></td>
                                </tr>
                            </tbody>
                        </table>
                </center>
            </td>
        </tr>
    </tbody>
</table>
<table valign="top" style="width:100%;max-width:680px;font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif; border-collapse: collapse; width: 100%;" width="100%" 
        cellspacing="0" cellpadding="0" border="0" align="center">
    <thead>
        <th style="padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #4CAF50; color: white; border: 1px solid #ddd; padding: 8px;">#</th>
        <th style="padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #4CAF50; color: white; border: 1px solid #ddd; padding: 8px;"><?= Yii::t('app', 'Name') ?></th>
        <th style="padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #4CAF50; color: white; border: 1px solid #ddd; padding: 8px;"></th>
    </thead>
    <tbody>
    <?php 
    $no         = 1; 
    ?>
    <?php foreach ($aProductOfUser as $p_id => $m): ?>
        <?php 
        $css = ($no%2==0) ? 'background-color: #f2f2f2;' : '';
        ?>
        <tr style="<?= $css ?>">
            <?php 
            $url = Yii::$app->request->serverName.Url::to(["/product/action/detail", 'url'=> $m->url]);
            ?>
            <td style="border: 1px solid #ddd; padding: 8px;">
                <?= $no++ ?>
            </td>
            <td style="border: 1px solid #ddd; padding: 8px;">
                    <?= $m->name ?>
            </td>
            <td style="border: 1px solid #ddd; padding: 8px;">
                    <?= Html::a(Yii::t('app', 'Detail'), $url).'<br>'
                       .Html::a(Yii::t('app', 'Go to shop'), $m->url) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>