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

use Yii;
use dektrium\user\traits\ModuleTrait;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string  $name
 * @property string  $public_email
 * @property string  $gravatar_email
 * @property string  $gravatar_id
 * @property string  $location
 * @property string  $website
 * @property string  $bio
 * @property string  $timezone
 * @property string  $business_name
 * @property string  $address
 * @property string  $city
 * @property string  $state
 * @property string  $zip
 * @property string  $phone
 * @property string  $google_page
 * @property string  $facebook_page
 * @property string  $trip_advisor
 * @property string  $yelp
 * @property string  $profile_url
 * @property string  $place_id
 * @property string  $logo
 * @property string  $feedback_approach_text
 * @property User    $user
 * @property User    $setup
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class Profile extends ActiveRecord
{
    use ModuleTrait;
    /** @var \dektrium\user\Module */
    protected $module;

    /**
     * @var UploadedFile
     */
    public $logo;

    /** @inheritdoc */
    public function init()
    {
        $this->module = \Yii::$app->getModule('user');
    }

    /**
     * Returns avatar url or null if avatar is not set.
     * @param  int $size
     * @return string|null
     */
    public function getAvatarUrl($size = 200)
    {
        return '//gravatar.com/avatar/' . $this->gravatar_id . '?s=' . $size;
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $profile = $this->module->modelMap['Profile'];
        $user = $this->module->modelMap['User'];

        return [
            'bioString'            => ['bio', 'string'],
            'timeZoneValidation'   => ['timezone', 'validateTimeZone'],
            'publicEmailPattern'   => ['public_email', 'email'],
            'gravatarEmailPattern' => ['gravatar_email', 'email'],
            'websiteUrl'           => ['website', 'url'],
            'nameLength'           => ['name', 'string', 'max' => 255],
            'publicEmailLength'    => ['public_email', 'string', 'max' => 255],
            'gravatarEmailLength'  => ['gravatar_email', 'string', 'max' => 255],
            'locationLength'       => ['location', 'string', 'max' => 255],
            'websiteLength'        => ['website', 'string', 'max' => 255],

            // 'nameRequired'         => ['name', 'required'],
            // 'businessNameRequired'         => ['business_name', 'required'],
            // 'websiteRequired'         => ['website', 'required'],
            // 'addressRequired'         => ['address', 'required'],
            // 'cityRequired'         => ['city', 'required'],
            // 'stateRequired'         => ['state', 'required'],
            // 'zipRequired'         => ['zip', 'required'],
            // 'phoneRequired'         => ['phone', 'required'],

            'required' => [['name', 'business_name', 'website', 'address', 'city', 'state', 'zip', 'phone'], 'required'],

            'businessNameLength'   => ['business_name', 'string', 'max' => 255],
            'addressLength'        => ['address', 'string'],
            'cityLength'           => ['city', 'string', 'max' => 255],
            'stateLength'          => ['state', 'string', 'max' => 255],
            'zipLength'            => ['zip', 'string', 'min'=>4, 'max' => 6],
            'zipNumber' => ['zip', 'number'],
            'googlePageLength'     => ['google_page', 'string', 'max' => 255],
            'phoneLength'          => ['phone', 'string', 'min' => 10, 'max'=>15],
            'googlePageUrl'     => ['google_page', 'url'],
            'facebookPageLength'   => ['facebook_page', 'string', 'max' => 255],
            'facebookPageUrl'   => ['facebook_page', 'url'],
            'tripAdvisorLength'    => ['trip_advisor', 'string', 'max' => 255],
            'tripAdvisorUrl'    => ['trip_advisor', 'url'],
            'yelpLength'           => ['yelp', 'string', 'max' => 255],
            'yelpUrl'           => ['yelp', 'url'],
            'profileUrlLength'     => ['profile_url', 'string', 'max' => 255],
            'profileUrlPattern'     => ['profile_url', 'match', 'pattern' => $user::$usernameRegexp],
            'profileUrlUnique'   => [
                'profile_url',
                'unique',
                'targetClass' => $profile,
                'message' => Yii::t('app', 'This Profile name has already been taken')
            ],
            'placeIdLength'     => ['place_id', 'string', 'max' => 255],
            //[['logo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg'],
            'logo' => ['logo', 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg'],
            // 'logo'   => ['logo', 'file'],
            // 'logo'   => ['logo', 'file', ['skipOnEmpty' => false, 'extensions' => 'png,jpg']],
            'feedbackApproachTextString'            => ['feedback_approach_text', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'           => \Yii::t('user', 'Contact Name'),
            'public_email'   => \Yii::t('user', 'Email (public)'),
            'gravatar_email' => \Yii::t('user', 'Gravatar email'),
            'location'       => \Yii::t('user', 'Location'),
            'website'        => \Yii::t('user', 'Website'),
            'bio'            => \Yii::t('user', 'Bio'),
            'timezone'       => \Yii::t('user', 'Time zone'),

            'business_name'   => \Yii::t('user', 'Business Name'),
            'address'        => \Yii::t('user', 'Address'),
            'city'           => \Yii::t('user', 'City'),
            'state'          => \Yii::t('user', 'State'),
            'zip'            => \Yii::t('user', 'Zip'),
            'phone'          => \Yii::t('user', 'Phone'),
            'google_page'     => \Yii::t('user', 'Google My Business Page'),
            'facebook_page'   => \Yii::t('user', 'Facebook Page'),
            'trip_advisor'    => \Yii::t('user', 'Trip Advisor'),
            'yelp'           => \Yii::t('user', 'Yelp'),
            'profile_url'     => \Yii::t('user', 'Profile Name'),
            'place_id'     => \Yii::t('user', 'Google-Place-Id'),
            'logo'     => \Yii::t('user', 'Company Logo'),
            'feedback_approach_text'     => \Yii::t('user', 'Feedback Approach Text'),
            'setup'     => \Yii::t('user', 'Account Setup Status'),
        ];
    }

    /**
     * Validates the timezone attribute.
     * Adds an error when the specified time zone doesn't exist.
     * @param string $attribute the attribute being validated
     * @param array $params values for the placeholders in the error message
     */
    public function validateTimeZone($attribute, $params)
    {
        if (!in_array($this->$attribute, timezone_identifiers_list())) {
            $this->addError($attribute, \Yii::t('user', 'Time zone is not valid'));
        }
    }

    /**
     * Get the user's time zone.
     * Defaults to the application timezone if not specified by the user.
     * @return \DateTimeZone
     */
    public function getTimeZone()
    {
        try {
            return new \DateTimeZone($this->timezone);
        } catch (\Exception $e) {
            // Default to application time zone if the user hasn't set their time zone
            return new \DateTimeZone(\Yii::$app->timeZone);
        }
    }

    /**
     * Set the user's time zone.
     * @param \DateTimeZone $timezone the timezone to save to the user's profile
     */
    public function setTimeZone(\DateTimeZone $timeZone)
    {
        $this->setAttribute('timezone', $timeZone->getName());
    }

    /**
     * Converts DateTime to user's local time
     * @param \DateTime the datetime to convert
     * @return \DateTime
     */
    public function toLocalTime(\DateTime $dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new \DateTime();
        }

        return $dateTime->setTimezone($this->getTimeZone());
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('gravatar_email')) {
            $this->setAttribute('gravatar_id', md5(strtolower(trim($this->getAttribute('gravatar_email')))));
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    public function upload()
    {
        if ($this->validate()) {
            if(!empty($this->logo)){
                $targetPath = Url::to('@webroot/images/logo/'.$this->user_id.'/', false);
                if (!file_exists($targetPath)) {
                    mkdir($targetPath, 0777);
                }
                $this->logo->saveAs($targetPath . $this->logo->baseName.'-'.time() . '.' . $this->logo->extension);
                return $this->logo->baseName.'-'.time() . '.' . $this->logo->extension;
            }
            return false;
        } else {
            return false;
        }
    }
}
