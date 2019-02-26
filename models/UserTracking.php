<?php
/**
 * Anivia *
 *	     __.oOo.__
 *	    /'(  _  )`\
 *	   / . \/^\/ . \
 *	  /  _)_`-'_(_  \
 *	 /.-~   ).(   ~-.\
 *	/'     /\_/\     `\
 *	     . "-V-"
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_tracking".
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property string $start_date
 * @property string $end_date
 */
class UserTracking extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_tracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_date', 'end_date', 'user_id', 'product_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'start_date' => 'Start Date',
            'end_date' => Yii::t('app', 'Track for'),
        ];
    }
    
    /*
     * get array tracking time
     */
    public static function aTrackingTime(){
        return [
            14 => Yii::t('app', '2 weeks'),
            30 => Yii::t('app', '1 month'),
            90 => Yii::t('app', '3 months'),
            180 => Yii::t('app', '6 months'),
            365 => Yii::t('app', '1 year'),
            0 => Yii::t('app', 'Until canceled'),
        ];
    }
    
    /*
     * get tracking items of user by id
     */
    public function getUserTrackingItems(){
        $models = UserTracking::find()->where(['user_id' => $this->user_id])->all();
        $ret    = [];
        if($models){
            foreach ($models as $row) {
                $ret[$row->product_id] = $row;
            }
        }
        return $ret;
    }
    
    public function isTracked(){
        $models = UserTracking::find()->where(['user_id' => Yii::$app->user->id, 'product_id' => $this->product_id])->one();
        return $models ? true : false;
    }
}
