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
use app\helpers\Constants;

/**
 * This is the model class for table "survey".
 *
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property int $type 1: checkbox, 2:radio, 3:text,...
 * @property int $status
 * @property string $created_date
 */
class Survey extends BaseModel
{
    const TYPE_CHECKBOX = 1;
    const TYPE_RADIO    = 2;
    const TYPE_TEXT     = 3;
    
    public static $aType = [
        self::TYPE_CHECKBOX => 'Checkbox',
        self::TYPE_RADIO    => 'Radio',
        self::TYPE_TEXT     => 'Text'
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question'], 'required', 'on'=>['create', 'update']],
            [['question','answer','type','status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'question' => Yii::t('app', 'Question'),
            'answer' => Yii::t('app', 'Answer'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_date' => Yii::t('app', 'Created Date'),
        ];
    }
    
    public function search($params)
    {
        $query = Survey::find();
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
        $query->andFilterWhere(['like', 'question', $this->question])
                ->andFilterWhere(['like', 'answer', $this->answer])
                ->andFilterWhere(['type'=>  $this->type])
                ->andFilterWhere(['status'=>  $this->status]);
        return $dataProvider;
    }
    
    public function getAnswer(){
        $result = $this->answer;
        return $result;
    }
    
    public function getType(){
        $aType = self::$aType;
        return isset($aType[$this->type]) ? $aType[$this->type] : '';
    }
    
    public function getStatus(){
        $aType = Constants::$aStatus;
        return isset($aType[$this->status]) ? $aType[$this->status] : '';
    }
    
    public function getListActive(){
        return Survey::find()
                ->where(['status' => STATUS_ACTIVE])
                ->orderBy(['id'=>SORT_DESC])
                ->all();
    }
    
    public function renderAnswer(){
        $aAnswer = explode(PHP_EOL, $this->answer);
        $result  = '';
//        $result .= '<input type="hidden" name="Survey[id][]" value="'.$this->id.'">';
        $isBreak = false;
        foreach ($aAnswer as $value) {
            switch ($this->type) {
                case self::TYPE_CHECKBOX:
                    $result .= '<label class="r-user-item cbcontainer" style="font-weight:normal;">';
                    $result .=      $value;
                    $result .=      '<input type="checkbox" name="Survey['.$this->id.'][answer][]" value="'.$value.'">';
                    $result .=      '<span class="checkmark"></span>';
                    $result .= '</label>';
                    break;
                case self::TYPE_RADIO:
                    $result .= '<label class="rdocontainer">'.$value;
                    $result .=      '<input type="radio" name="Survey['.$this->id.'][answer][]" value="'.$value.'">';
                    $result .=      '<span class="checkmark"></span>';
                    $result .= '</label>';
                    break;
                case self::TYPE_TEXT:
                    $result .= '<div class="form-group">';
                    $result .=      '<textarea class="form-control" rows="5" name="Survey['.$this->id.'][answer]"></textarea>';
                    $result .= '</div>';
                    $isBreak = true;
                    break;

                default:
                    $isBreak = true;
                    break;
            }
            if($isBreak) break;
        }
        return $result;
    }
    
}
