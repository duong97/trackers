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
use yii\data\ActiveDataProvider;
use app\helpers\MyFormat;
use app\models\Survey;
use app\models\Users;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property int $survey_id
 * @property int $user_id
 * @property string $ip
 * @property string $another_info
 * @property string $answer
 * @property string $created_date
 */
class Feedback extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'survey_id' => Yii::t('app', 'Survey ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'ip' => Yii::t('app', 'Ip'),
            'another_info' => Yii::t('app', 'Another Info'),
            'answer' => Yii::t('app', 'Answer'),
            'created_date' => Yii::t('app', 'Created Date'),
        ];
    }
    
    /**
     * @todo relation Survey
     */
    public function getRSurvey(){
        return $this->hasOne(Survey::className(), ['id' => 'survey_id']);
    }
    
    /**
     * @todo relation User
     */
    public function getRUser(){
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
    
    public function getQuestion(){
        return isset($this->rSurvey) ? $this->rSurvey->question : '';
    }
    
    public function getUser(){
        return isset($this->rUser) ? $this->rUser->first_name : 'Guest | '.$this->ip;
    }
    
    public function getAnswer(){
        $aAnser = json_decode($this->answer, true);
        $result = '';
        if(is_array($aAnser)){
            foreach ($aAnser as $value) {
                $result .= $value."<br>";
            }
        } else {
            $result = $aAnser;
        }
        return $result;
    }
    
    public function can($action){
        $aActionNotAllow = ['create','update'];
        if(in_array($action, $aActionNotAllow)){
            return false;
        }
        return parent::can($action);
    }
    
    public function search($params)
    {
        $query = Feedback::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            'pagination' => [ 
                'pageSize'=> isset(Yii::$app->params['defaultPageSize']) ? Yii::$app->params['defaultPageSize'] : 10,
            ],
        ]);
        // No search? Then return data Provider
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        // We have to do some search... Lets do some magic
        $query->andFilterWhere(['like', 'name', $this->name]);
//        ->andFilterWhere([]);
        return $dataProvider;
    }
    
    public function beforeSave($insert) {
        $this->ip           = Yii::$app->request->getUserIP();
        $this->user_id      = MyFormat::getCurrentUid();
        $this->another_info = Yii::$app->request->getUserAgent();
        return parent::beforeSave($insert);
    }
    
}
