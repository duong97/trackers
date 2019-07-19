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
use app\models\UploadForm;
use app\helpers\MyFormat;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "blog".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $thumb
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property string $created_date
 */
class Blog extends BaseModel
{
    const TYPE_NEWS         = 1;
    const TYPE_VOUCHER      = 2;
    const TYPE_PROMOTION    = 3;
    
    const STATUS_PUBLIC     = 1;
    const STATUS_PRIVATE    = 2;
    const STATUS_DRAFT      = 3;
    
    const UNIT_POST         = 'bài';
    
    public static $aType = [
        self::TYPE_NEWS         => 'Tin tức',
        self::TYPE_VOUCHER      => 'Mã giảm giá',
        self::TYPE_PROMOTION    => 'Khuyến mãi',
    ];
    
    public static $aStatus = [
        self::STATUS_PUBLIC     => 'Công khai',
        self::STATUS_PRIVATE    => 'Riêng tư',
        self::STATUS_DRAFT      => 'Bản nháp',
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        // thumb ko dc để safe, vì khi load data from post lúc update sẽ bị mất value cũ
        return [
            [['title','description','content','type', 'status', 'created_by','created_date'], 'safe'],
            [['title'], 'required', 'on'=> ['create', 'update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'content' => Yii::t('app', 'Content'),
            'thumb' => Yii::t('app', 'Thumbnail'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
        ];
    }
    
    /**
     * @todo relation
     */
    public function getRCreatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }
    
    public function getCreatedBy(){
        if(isset($this->rCreatedBy)){
            return $this->rCreatedBy->getFullName();
        }
        return '';
    }
    
    public function getType(){
        $aType = Blog::$aType;
        return isset($aType[$this->type]) ? $aType[$this->type] : '';
    }
    
    public function getStatus(){
        $aStatus = Blog::$aStatus;
        return isset($aStatus[$this->status]) ? $aStatus[$this->status] : '';
    }
    
    public function getThumbnailUrl(){
        if( empty($this->thumb) ) return '';
        $mUploadForm = new UploadForm();
        return $mUploadForm->getRelativeUploadPath().$this->thumb;
    }
    
    public function getThumbnailHtml($size = 150){
        $url = $this->getThumbnailUrl();
        return Html::img($url, ['style'=>'max-width:'.$size.'px;']);
    }
    
    public function getUrlUserView(){
        return Url::to(['/site/blog', 'view'=> $this->getSlug()]);
    }
    
    public function getSlug(){
        return MyFormat::slugify($this->title).'--'.$this->id;
    }
    
    public function getModelFromSlug($slug){
        $tmpData    = explode('--', $slug);
        $id         = isset($tmpData[1]) ? $tmpData[1] : null;
        return Blog::findOne($id);
    }
    
    public function search($params)
    {
        $query = Blog::find();
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
        $this->created_date = MyFormat::formatSqlDate($this->created_date);
        
        // We have to do some search... Lets do some magic
        $query->andFilterWhere(['like', 'LOWER(title)', $this->title])
                ->andFilterWhere(['type'=> $this->type, 'status'=> $this->status, 'DATE(created_date)'=> $this->created_date])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'description', $this->description]);
//        ->andFilterWhere([]);
        return $dataProvider;
    }
    
    /**
     * @todo Thống kê số bài viết ở site/blog
     */
    public function getReportForList(){
        $ret = [];
        $today = date('Y-m-d');
        $models = Blog::find()
                            ->select(['count(id) as id', 'type', 'DATE(created_date) as created_date'])
                            ->where(['status' => Blog::STATUS_PUBLIC])
                            ->groupBy(['type', 'DATE(created_date)'])
                            ->all();
        foreach ($models as $value) {
            MyFormat::addValue($ret['ALL_POST'], $value->id);
            MyFormat::addValue($ret['POST_BY_TYPE'][$value->type], $value->id);
            if($value->created_date == $today){
                MyFormat::addValue($ret['TODAY_POST'], $value->id);
            }
        }
        return $ret;
    }
    
    
    
}
