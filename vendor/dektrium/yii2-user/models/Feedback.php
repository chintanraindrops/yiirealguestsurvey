<?php

namespace dektrium\user\models;

use dektrium\user\Finder;
use dektrium\user\helpers\Password;
use dektrium\user\Mailer;
use dektrium\user\Module;
use dektrium\user\traits\ModuleTrait;
//use dektrium\user\models\FeedbackQuery;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\Application as WebApplication;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $from_firstname
 * @property string $from_lastname
 * @property string $from_email
 * @property string $from_mobile
 * @property string $notes
 * @property integer $created_at
 *
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class Feedback extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @var integer id
     */
    public $id;
    /**
     * @var integer user_id
     */
    public $user_id;
    /**
     * @var integer location_id
     */
    public $location_id;
    /**
     * @var string type
     */
    public $type;
    /**
     * @var string from_firstname
     */
    public $from_firstname;
    /**
     * @var string from_lastname
     */
    public $from_lastname;
    /**
     * @var string from_email
     */
    public $from_email;
    /**
     * @var string from_mobile
     */
    public $from_mobile;
    /**
     * @var string from_token
     */
    public $from_token;
    /**
     * @var string notes
     */
    public $notes;
    /**
     * @var integer created_at
     */
    public $created_at;
    /**
     * @var integer contact_me
     */
    public $contact_me;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'location_id' => Yii::t('app', 'Location'),
            'type' => Yii::t('app', 'Type'),
            'from_firstname' => Yii::t('app', 'Name'),
            'from_lastname' => Yii::t('app', 'Lastname'),
            'from_email' => Yii::t('app', 'Email'),
            'from_mobile' => Yii::t('app', 'Mobile'),
            'from_token' => Yii::t('app', 'Token'),
            'notes' => Yii::t('app', 'Notes'),
            'created_at' => Yii::t('app', 'Created At'),
            'contact_me' => Yii::t('app', 'Contact Me'),
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            'feedback' => ['id', 'user_id', 'location_id', 'type', 'from_firstname', 'from_lastname', 'from_email', 'from_mobile', 'from_token', 'notes', 'created_at', 'contact_me'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['location_id'], 'integer'],
            [['notes'], 'string'],
            [['from_token'], 'string'],
            [['type', 'from_firstname', 'from_lastname', 'from_email', 'from_mobile'], 'string', 'max' => 255],
            // firstname rules
            'firstnameLength'   => ['from_firstname', 'string', 'min' => 3, 'max' => 255],
            'firstnameTrim'     => ['from_firstname', 'filter', 'filter' => 'trim'],
            'firstnameRequired' => ['from_firstname', 'required', 'when' => function() {
                                    return \Yii::$app->controller->action->id == 'feedbackthankyou';
                                }],
            // lastname rules
            'lastnameLength'   => ['from_lastname', 'string', 'min' => 3, 'max' => 255],
            'lastnameTrim'     => ['from_lastname', 'filter', 'filter' => 'trim'],
            /*'lastnameRequired' => ['from_lastname', 'required', 'when' => function() {
                                    return \Yii::$app->controller->action->id == 'feedbackthankyou';
                                }],*/
            // email rules
            'emailTrim'     => ['from_email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['from_email', 'required', 'when' => function() {
                                    return \Yii::$app->controller->action->id == 'feedbackthankyou';
                                }],
            'emailPattern'  => ['from_email', 'email'],
            // mobile rules
            //'mobileRequired' => ['mobile', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            'mobileLength'   => ['from_mobile', 'string', 'min' => 10, 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }*/

    /**
     * @inheritdoc
     * @return FeedbackQuery the active query used by this AR class.
     */
    /*public static function find()
    {
        return new FeedbackQuery(get_called_class());
    }*/

    public function one($db = null)
    {
        return parent::findOne($db);
    }

    public function customFindOne($id){
        $findmodel = $this->findOne($id);
        var_dump($findmodel);exit;
    }

    public static function findExistFeedback($feed_id, $location_id, $user_id){
        $yestertime = strtotime("-1 day");
        $currenttime = time();
        //var_dump($time);exit;
        if(!empty($feed_id) && !empty($user_id)){
            $num_of_exist_records = (new \yii\db\Query())
            ->from('feedback')
            ->where('(from_email = \''.$feed_id.'\' OR from_mobile = \''.$feed_id.'\') AND location_id = \''.$location_id.'\' AND user_id = \''.$user_id.'\' AND created_at between \''.$yestertime.'\' AND \''.$currenttime.'\'')
            ->count();

            if($num_of_exist_records > 0){
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className='Feedback')
    {
        return parent::model($className);
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
        );
    }

    public function setData(){
        $this->setAttribute("user_id", (!empty($this->user_id)) ? $this->user_id : $this->getAttribute("user_id"));
        $this->setAttribute("location_id", (!empty($this->location_id)) ? $this->location_id : $this->getAttribute("location_id"));
        $this->setAttribute("from_firstname", $this->from_firstname);
        $this->setAttribute("from_lastname", $this->from_lastname);
        $this->setAttribute("from_email", $this->from_email);
        $this->setAttribute("from_mobile", $this->from_mobile);
        $this->setAttribute("from_token", $this->from_token);
        $this->setAttribute("notes", (!empty($this->notes)) ? $this->notes : $this->getAttribute("notes"));
        $this->setAttribute("type", (!empty($this->type)) ? $this->type : $this->getAttribute("type"));
        $this->setAttribute("created_at", time());
        //var_dump($this->getAttribute("contact_me"));exit;
        $this->setAttribute("contact_me", (!empty($this->contact_me)) ? $this->contact_me : ($this->getAttribute("contact_me")>0) ? 1 : 0  );
        return $this->loadAttributes($this);
    }

    protected function loadAttributes(Feedback $Feedback)
    {   
        // echo "<pre>"; print_r($this->attributes);
        $Feedback->setAttributes($this->attributes);
    }
}
