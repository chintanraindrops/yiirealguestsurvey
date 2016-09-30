<?php

namespace dektrium\user\models;

use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use dektrium\user\Mailer;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class ProfileForm extends Model
{
    use ModuleTrait;
    
    /**
     * @var string name
     */
    public $name;

    /**
     * @var string business_name
     */
    public $business_name;

    /**
     * @var string phone
     */
    public $phone;

    /**
     * @var string website
     */
    public $website;

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
     * @var string setup
     */
    public $setup;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'nameLength'   => ['name', 'string', 'min' => 3, 'max' => 255],
            'nameTrim'     => ['name', 'filter', 'filter' => 'trim'],
            'nameRequired' => ['name', 'required'],

            'businessNameLength'   => ['business_name', 'string', 'min' => 3, 'max' => 255],
            'businessNameTrim'     => ['business_name', 'filter', 'filter' => 'trim'],
            'businessNameRequired' => ['business_name', 'required'],

            'addressLength'   => ['address', 'string', 'min' => 3, 'max' => 255],
            'addressTrim'     => ['address', 'filter', 'filter' => 'trim'],

            'cityLength'   => ['city', 'string', 'max' => 255],
            'cityTrim'     => ['city', 'filter', 'filter' => 'trim'],

            'stateLength'   => ['state', 'string', 'max' => 255],
            'stateTrim'     => ['state', 'filter', 'filter' => 'trim'],

            'zipLength' => ['zip', 'string', 'min'=>4, 'max' => 6],
            'zipNumber' => ['zip', 'number'],
            'zipTrim'     => ['zip', 'filter', 'filter' => 'trim'],

            'websiteLength'   => ['website', 'string', 'min' => 3, 'max' => 255],
            'websiteTrim'     => ['website', 'filter', 'filter' => 'trim'],

            'phoneRequired' => ['phone', 'required'],
            'phoneLength'          => ['phone', 'string', 'min' => 10, 'max'=>15],

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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('user', 'Contact Name'),
            'business_name' => Yii::t('user', 'Business Name'),
            'website' => Yii::t('user', 'Website'),
            'address' => Yii::t('user', 'Address'),
            'city' => Yii::t('user', 'City'),
            'state' => Yii::t('user', 'State'),
            'zip' => Yii::t('user', 'Zip'),
            'phone' => Yii::t('user', 'Phone'),

            'google_page' => Yii::t('user', 'Google My Business Page'),
            'facebook_page' => Yii::t('user', 'Facebook Page'),
            'trip_advisor' => Yii::t('user', 'Trip Advisor'),
            'yelp' => Yii::t('user', 'Yelp'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'Profile';
    }

    /**
     * @return Mailer
     * @throws \yii\base\InvalidConfigException
     */
    protected function getMailer()
    {
        return \Yii::$container->get(Mailer::className());
    }

    /**
     * Registers a new user account. If registration was successful it will set flash message.
     *
     * @return bool
     */
    public function request($profile)
    {
        if (!$this->validate()) {
            return false;
        }
        return $this->loadAttributes($profile);
    }

    public function requestContact(){
        $username = \Yii::$app->user->identity->getUsername();
        $password = \Yii::$app->user->identity->getPassword();
        // $email = "chintan@raindropsinfotech.com";
        if($this->mailer->sendAccountsetupMessage2($username, $password, $this)){
            $test = \Yii::$app->user->logout();
            return $test;
        } else {
            return false;
        }
    }

    public function requestContact3($locations){
        $username = \Yii::$app->user->identity->getUsername();
        $password = \Yii::$app->user->identity->getPassword();
        // $email = "chintan@raindropsinfotech.com";
        if($this->mailer->sendAccountsetupMessage3($username, $password, $this, $locations)){
            $test = \Yii::$app->user->logout();
            return $test;
        } else {
            return false;
        }
    }

    /**
     * Loads attributes to the user model. You should override this method if you are going to add new fields to the
     * registration form. You can read more in special guide.
     *
     * By default this method set all attributes of this model to the attributes of User model, so you should properly
     * configure safe attributes of your User model.
     *
     * @param User $user
     */
    protected function loadAttributes(Profile $profile)
    {
        $profile->setAttributes($this->attributes);
    }
}
