<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\helpers;
use yii\web\NotFoundHttpException;

class Checks{
    public function notFoundExc() {
//        throw new NotFoundHttpException("hihi");
        throw new NotFoundHttpException("The request page does not exists!");
    }
}