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
 * This is the model class for table "loggers".
 *
 * @property int $id
 * @property string $message
 * @property string $ip
 * @property int $type
 * @property string $created_date
 */
class Loggers extends BaseModel
{
    const type_debug = 1;
    const type_cron  = 2;
    const type_app   = 3; //application error
    const type_info  = 4;
    
    public static $aLogType = [
        self::type_debug    => 'Debug',
        self::type_cron     => 'Cron',
        self::type_app      => 'Application Error',
        self::type_info     => 'Info',
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loggers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['type'], 'integer'],
            [['created_date'], 'safe'],
            [['ip'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'message' => Yii::t('app', 'Message'),
            'ip' => Yii::t('app', 'Ip'),
            'type' => Yii::t('app', 'Type'),
            'created_date' => Yii::t('app', 'Created Date'),
        ];
    }
    
    public static function WriteLog($message, $type=self::type_debug) {
        $model          = new Loggers();
        $model->message = $message;
        $model->type    = $type;
        $model->ip      = ($type == self::type_debug) ? Yii::$app->request->userIP : '';
        $model->save();
    }
}
