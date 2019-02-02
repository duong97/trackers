<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::setAlias('@root', dirname(__DIR__));

Yii::$classMap['Constants'] = '@root/helpers/Constants.php';
Yii::$classMap['Checks']    = '@root/helpers/Checks.php';
