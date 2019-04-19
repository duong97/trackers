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

/**
 * This is the model class for table "user_data".
 *
 * @property int $id
 * @property int $user_id
 * @property string $search_keywords
 */
class UserData extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','search_keywords'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'search_keywords' => Yii::t('app', 'Search Keywords'),
        ];
    }
    
    public function search($params)
    {
        $query = UserData::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => [
//                'defaultOrder' => [
//                    'created_date' => SORT_DESC,
//                ]
//            ],
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
    
    /**
     * @todo Handle user's keyword search, save if not exists
     */
    public function handleUserKeyword($keyword){
        $mUserData = UserData::findOne(['user_id' => Yii::$app->user->id]);
        if(empty($mUserData)){
            $mUserData = new UserData();
            $mUserData->user_id = Yii::$app->user->id;
        }
        $mUserData->getKeyword();
        $aKeyword  = is_null($mUserData->search_keywords) ? [] : $mUserData->search_keywords;
        $key_pos   = array_search($keyword, $aKeyword);
        if( $key_pos !== false ){
            unset($aKeyword[$key_pos]);
            array_unshift($aKeyword, $keyword);
        } else {
            array_unshift($aKeyword, $keyword);
        }
        $mUserData->search_keywords = json_encode($aKeyword);
        $mUserData->isNewRecord ? $mUserData->save() : $mUserData->update();
    }
    
    /**
     * @todo get keyword
     */
    public function getKeyword(){
        $this->search_keywords  = json_decode($this->search_keywords, true);
        return $this->search_keywords;
    }
    
    /**
     * @todo get keyword of current user
     */
    public function getCurrentKeyword(){
        $mUserData = UserData::findOne(['user_id' => Yii::$app->user->id]);
        if(empty($mUserData)){
            return [];
        }
        $mUserData->getKeyword();
        return $mUserData->search_keywords;
    }
    
    /**
     * @todo get keyword for search nav
     */
    public function getKeywordNav(){
        $aKeyWord = [
            ['label' => 'Giày', 'category' => ''],
            ['label' => 'Đồng hồ', 'category' => ''],
            ['label' => 'Áo khoác', 'category' => ''],
            ['label' => 'Túi xách', 'category' => ''],
        ];
        if (!Yii::$app->user->isGuest) {
            $mUserData = new UserData();
            $aUserKW = $mUserData->getCurrentKeyword();
            foreach ($aUserKW as $value) {
                $aKeyWord[] = [
                    'label' => $value, 
                    'category' => Yii::t('app', 'Search History')
                ];
            }
        }
        return $aKeyWord;
    }
    
    
}
