<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png'],
        ];
    }
    
    /**
     * @todo upload one or more file
     * @params $folder path to folder to upload
     * @params $filename file name to save (not file extension)
     */
    public function upload($folder, $aFileName = '')
    {
        $aImage              = array_filter($this->imageFile);
        foreach ($aImage as $key => $file) {
            $this->imageFile = $file;
            if( $this->validate() ){
                $filename    = isset($aFileName[$key]) ? $aFileName[$key] : $file->baseName;
                $file->saveAs( $folder . $filename.'.'.$file->extension);
            } else {
                return false;
            }
        }
        return true;
    }
    
    
}