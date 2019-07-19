<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\models\Files;
use Yii;

class UploadForm extends Model
{
    const LIMIT_IMAGE_SIZE = 10000000; // 10 MB
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $aImageFile;
    public $pathAfterUpload;
    
    public static $aImageExtension = [
        'png',
        'jpg',
        'jpeg'
    ];
    
    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => implode(',', self::$aImageExtension)],
        ];
    }
    
    /**
     * @todo get full path /var/www/html/trackers/web/images/support_website
     * for upload image to server
     */
    public function getUploadPath(){
        return Yii::getAlias('@root').'/web/images/upload/';
    }
    
    /**
     * @todo get relative path for image src /web/images/support_website/tiki_logo.png
     * for get image from server
     */
    public function getRelativeUploadPath(){
        return Yii::getAlias('@web').'/images/upload/';
    }
    
    /**
     * @todo filter file type, size before upload
     */
    public function filterBeforeUpload($isMulti = false){
        if( is_array($this->aImageFile) ): // filter array image
            foreach ($this->aImageFile as $key => $mUploadFile) {
                $typeDetail     = isset($mUploadFile->type) ? $mUploadFile->type : null;
                $size           = isset($mUploadFile->size) ? $mUploadFile->size : null;
                $typeGeneral    = isset(explode('/', $typeDetail)[0]) ? explode('/', $typeDetail)[0] : '';
                $extension      = isset(explode('/', $typeDetail)[1]) ? explode('/', $typeDetail)[1] : '';
                if($size > self::LIMIT_IMAGE_SIZE || $typeGeneral != 'image' || !in_array($extension, self::$aImageExtension)){
                    unset($this->aImageFile[$key]);
                }
            }
            $this->aImageFile = array_values($this->aImageFile); // reindex array image
        endif;
        
        if( !$isMulti ){ // if not multi, choose the last
            $this->aImageFile = array_slice($this->aImageFile, 0, 1); // return array
        }
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
    
    /**
     * use this function for widget upload file
     * @param $model object model, have id to save belong_id
     * @param $attribute attribute name
     * @param $type type of model (blog, ..)
     * @param $isMulti is multiple files
     */
    public function handleUpload($model, $attribute, $type, $isMulti = false){
        $this->aImageFile   = UploadedFile::getInstances($model, $attribute);
        if( empty($this->aImageFile) ){
            return false;
        }
        $this->filterBeforeUpload($isMulti);
        $tmpName            = $type.'_'.strtotime('now');
        if( is_array($this->aImageFile) ):
            foreach ($this->aImageFile as $key => $file) {
                $filename = $tmpName.'_'.$key.'.'.$file->extension;
                $file->saveAs( $this->getUploadPath().$filename );
                if( !$isMulti ){
                    $this->pathAfterUpload = $filename; // assign the path to var
                }
                $mFiles             = new Files();
                $mFiles->name       = $filename;
                $mFiles->type       = $type;
                $mFiles->belong_id  = $model->id;
                $mFiles->order      = $key;
                $mFiles->save();
            }
        endif;
    }
    
}