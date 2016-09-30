<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dektrium\user\models;

use yii;
use dektrium\user\Finder;
use dektrium\user\helpers\Password;
use dektrium\user\Mailer;
use dektrium\user\Module;
use dektrium\user\traits\ModuleTrait;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\Application as WebApplication;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use yii\validators\UniqueValidator;
use yii\web\UploadedFile;

/**
 *
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class BusinessLocation extends ActiveRecord // implements IdentityInterface
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
     * @var string business_name
     */
    public $business_name;
    /**
     * @var string location_name
     */
    public $location_name;
    /**
     * @var string address
     */
    public $address;
    /**
     * @var string city
     */
    public $city;
    /**
     * @var string state
     */
    public $state;
    /**
     * @var string zip
     */
    public $zip;
    /**
     * @var string phone
     */
    public $phone;

    /**
     * @var string google_page
     */
    public $google_page;

    /**
     * @var string facebook_page
     */
    public $facebook_page;

    /**
     * @var string trip_advisor
     */
    public $trip_advisor;

    /**
     * @var string yelp
     */
    public $yelp;

    /**
     * @var string google_place_id
     */
    public $google_place_id;

    /**
     * @var string feedback_approach_text
     */
    public $feedback_approach_text;

    /**
     * @var string profile_url
     */
    public $profile_url;

    /**
     * @var UploadedFile
     */
    public $logo;


    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'id'          => \Yii::t('user', 'Location Id'),
            'user_id'          => \Yii::t('user', 'User'),
            'business_name'          => \Yii::t('user', 'Business Name'),
            'location_name'          => \Yii::t('user', 'Location Name'),
            'address'          => \Yii::t('user', 'Address'),
            'city'             => \Yii::t('user', 'city'),
            'state'          => \Yii::t('user', 'State'),
            'zip'        => \Yii::t('user', 'Zip'),
            'phone'        => \Yii::t('user', 'Phone'),
            'google_page' => \Yii::t('user', 'Google My Business Page'),
            'facebook_page' => \Yii::t('user', 'Facebook Page'),
            'trip_advisor' => \Yii::t('user', 'Trip Advisor'),
            'yelp' => \Yii::t('user', 'Yelp'),
            'google_place_id' => \Yii::t('user', 'Google Place Id'),
            'feedback_approach_text' => \Yii::t('user', 'Feedback Approach Text'),
            'profile_url' => \Yii::t('user', 'Profile Name'),
            'logo'     => \Yii::t('user', 'Company Logo'),
        ];
    }

    /** @inheritdoc */
    // public function scenarios()
    // {
    //     $scenarios = parent::scenarios();
    //     return ArrayHelper::merge($scenarios, [
    //         'request' => ['firstname', 'lastname', 'email', 'mobile'],
    //     ]);
    // }

    /** @inheritdoc */
    public function rules()
    {
        $profile = $this->module->modelMap['Profile'];
        $user = $this->module->modelMap['User'];

        return [
            'UserIdRequired' => ['user_id', 'required'],

            'businessNameLength'   => ['business_name', 'string', 'min' => 3, 'max' => 255],
            'businessNameTrim'     => ['business_name', 'filter', 'filter' => 'trim'],
            'businessNameRequired' => ['business_name', 'required'],

            'locationNameLength'   => ['location_name', 'string', 'min' => 3, 'max' => 255],
            'locationNameTrim'     => ['location_name', 'filter', 'filter' => 'trim'],
            'locationNameRequired' => ['location_name', 'required'],

            'addressLength'   => ['address', 'string', 'min' => 3, 'max' => 255],
            'addressTrim'     => ['address', 'filter', 'filter' => 'trim'],

            'cityLength'   => ['city', 'string', 'max' => 255],
            'cityTrim'     => ['city', 'filter', 'filter' => 'trim'],

            'stateLength'   => ['state', 'string', 'max' => 255],
            'stateTrim'     => ['state', 'filter', 'filter' => 'trim'],

            'zipLength'   => ['zip', 'string', 'min' => 3, 'max' => 6],
            'zipTrim'     => ['zip', 'filter', 'filter' => 'trim'],

            'phoneRequired' => ['phone', 'required'],
            'phoneLength'   => ['phone', 'string', 'min' => 10, 'max' => 15],

            'googlePageLength'   => ['google_page', 'string', 'min' => 3, 'max' => 255],
            'googlePageTrim'     => ['google_page', 'filter', 'filter' => 'trim'],
            'googlePageUrl'     => ['google_page', 'url'],

            'facebookPageLength'   => ['facebook_page', 'string', 'min' => 3, 'max' => 255],
            'facebookPageTrim'     => ['facebook_page', 'filter', 'filter' => 'trim'],
            'facebookPageUrl'   => ['facebook_page', 'url'],

            'tripAdvisorLength'   => ['trip_advisor', 'string', 'min' => 3, 'max' => 255],
            'tripAdvisorTrim'     => ['trip_advisor', 'filter', 'filter' => 'trim'],
            'tripAdvisorUrl'    => ['trip_advisor', 'url'],

            'yelpLength'   => ['yelp', 'string', 'min' => 3, 'max' => 255],
            'yelpTrim'     => ['yelp', 'filter', 'filter' => 'trim'],
            'yelpUrl'           => ['yelp', 'url'],

            'googlePlaceIdLength'   => ['google_place_id', 'string', 'min' => 3, 'max' => 255],
            'googlePlaceIdTrim'     => ['google_place_id', 'filter', 'filter' => 'trim'],

            'feedbackApproachTextLength'   => ['feedback_approach_text', 'string'],
            'feedbackApproachTextTrim'     => ['feedback_approach_text', 'filter', 'filter' => 'trim'],

            'profileUrlLength'     => ['profile_url', 'string', 'max' => 255],
            'profileUrlPattern'     => ['profile_url', 'match', 'pattern' => $user::$usernameRegexp],
            'profileUrlUnique'   => [
                'profile_url',
                'unique',
                //'targetAttribute' => 'profile_url',
                'targetClass' => 'dektrium\user\models\BusinessLocation', //$profile,
                'message' => Yii::t('user', 'This Profile name has already been taken')
            ],
            'logo' => ['logo', 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg'],

            // ['profile_url', 'unique'],
        ];
    }


    public function request()
    {
        $this->user_id = \Yii::$app->user->id;
        $this->business_name = $this->getAttribute('business_name');
        $this->location_name = $this->getAttribute('location_name');
        $this->address = $this->getAttribute('address');
        $this->city = $this->getAttribute('city');
        $this->state = $this->getAttribute('state');
        $this->zip = $this->getAttribute('zip');
        $this->phone = $this->getAttribute('phone');
        $this->google_page = $this->getAttribute('google_page');
        $this->facebook_page = $this->getAttribute('facebook_page');
        $this->trip_advisor = $this->getAttribute('trip_advisor');
        $this->yelp = $this->getAttribute('yelp');
        $this->google_place_id = $this->getAttribute('google_place_id');
        $this->feedback_approach_text = $this->getAttribute('feedback_approach_text');
        $this->profile_url = $this->getAttribute('profile_url');
        $this->logo = $this->getAttribute('logo');
        return $this->save();
    }


    
    /** @inheritdoc */
    public static function tableName()
    {
        return 'business_location';
    }

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className='BusinessLocation')
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

    protected function loadAttributes(BusinessLocation $businessLocation)
    {   
        $businessLocation->setAttributes($this->attributes);
    }

}
