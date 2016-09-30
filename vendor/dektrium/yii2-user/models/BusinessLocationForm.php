<?php

namespace dektrium\user\models;

use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use dektrium\user\Mailer;
use yii\validators\UniqueValidator;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class BusinessLocationForm extends Model
{
    use ModuleTrait;
    
    /**
     * @var integer id
     */
    public $id;

    /**
     * @var integer id
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        //$profile = $this->module->modelMap['Profile'];
        $user = $this->module->modelMap['User'];

        return [
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
                'targetAttribute' => 'profile_url',
                'targetClass' => 'dektrium\user\models\BusinessLocation', //$profile,
                'message' => Yii::t('user', 'This Profile name has already been taken')
            ],

            'logo' => ['logo', 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg'],

            // ['profile_url', 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'Location Id'),
            'business_name' => Yii::t('user', 'Business Name'),
            'location_name' => Yii::t('user', 'Location Name'),
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
            'google_place_id' => Yii::t('user', 'Google Place Id'),
            'feedback_approach_text' => Yii::t('user', 'Feedback Approach Text'),
            'profile_url' => Yii::t('user', 'Link (e.g. surveylocal.com/Link): '),
            'logo'     => \Yii::t('user', 'Company Logo'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'business-location';
    }

    /**
     * @return Mailer
     * @throws \yii\base\InvalidConfigException
     */
    protected function getMailer()
    {
        return \Yii::$container->get(Mailer::className());
    }

    // /**
    //  * Registers a new user account. If registration was successful it will set flash message.
    //  *
    //  * @return bool
    //  */
    // public function request($businessLocation)
    // {
    //     if (!$this->validate()) {
    //         return false;
    //     }
    //     return $this->loadAttributes($businessLocation);
    // }

    public function request($business_location){
        
        $locations = BusinessLocation::find()->where(['user_id' => \Yii::$app->user->id])->all();
        $data = $business_location['business-location'];
        //echo '<pre>';print_r($locations);exit;
        unset($data['id']);
        foreach ($locations as $location) {
            if($location->getAttribute("business_name") == $data['business_name'] 
                && $location->getAttribute("address") == $data['address']
                && $location->getAttribute("location_name") == $data['location_name']
                && $location->getAttribute("city") == $data['city']
                && $location->getAttribute("state") == $data['state']
                && $location->getAttribute("zip") == $data['zip']
                && $location->getAttribute("phone") == $data['phone']
            ){
                // echo $location->getAttribute("id"); exit;
                return 1;
                return BusinessLocation::updateAll($data, ['id' => $location->getAttribute("id")]);
                // echo "tesT".$location->update($data); exit;
            }
        }

        $businessLocation = Yii::createObject(BusinessLocation::className());
        
        $businessLocation->user_id = Yii::$app->user->id;
        // $businessLocation->id = $business_location['business-location']['id'];
        $businessLocation->business_name = $business_location['business-location']['business_name'];
        $businessLocation->location_name = $business_location['business-location']['location_name'];
        $businessLocation->address = $business_location['business-location']['address'];
        $businessLocation->city = $business_location['business-location']['city'];
        $businessLocation->state = $business_location['business-location']['state'];
        $businessLocation->zip = $business_location['business-location']['zip'];
        $businessLocation->phone = $business_location['business-location']['phone'];
        $businessLocation->google_page = $business_location['business-location']['google_page'];
        $businessLocation->facebook_page = $business_location['business-location']['facebook_page'];
        $businessLocation->trip_advisor = $business_location['business-location']['trip_advisor'];
        $businessLocation->yelp = $business_location['business-location']['yelp'];
        $businessLocation->google_place_id = $business_location['business-location']['google_place_id'];
        $businessLocation->feedback_approach_text = $business_location['business-location']['feedback_approach_text'];
         //$businessLocation->profile_url = $business_location['business-location']['profile_url'];

        $businessLocation->setAttribute("user_id", Yii::$app->user->id);
        // $businessLocation->setAttribute("id", $business_location['business-location']['id']);
        $businessLocation->setAttribute("business_name", $business_location['business-location']['business_name']);
        $businessLocation->setAttribute("location_name", $business_location['business-location']['location_name']);
        $businessLocation->setAttribute("address", $business_location['business-location']['address']);
        $businessLocation->setAttribute("city", $business_location['business-location']['city']);
        $businessLocation->setAttribute("state", $business_location['business-location']['state']);
        $businessLocation->setAttribute("zip", $business_location['business-location']['zip']);
        $businessLocation->setAttribute("phone", $business_location['business-location']['phone']);
        $businessLocation->setAttribute("google_page", $business_location['business-location']['google_page']);
        $businessLocation->setAttribute("facebook_page", $business_location['business-location']['facebook_page']);
        $businessLocation->setAttribute("trip_advisor", $business_location['business-location']['trip_advisor']);
        $businessLocation->setAttribute("yelp", $business_location['business-location']['yelp']);
        $businessLocation->setAttribute("google_place_id", $business_location['business-location']['google_place_id']);
        $businessLocation->setAttribute("feedback_approach_text", $business_location['business-location']['feedback_approach_text']);
        //$businessLocation->setAttribute("profile_url", $business_location['business-location']['profile_url']);
        
        // if (!$businessLocation->validate()) {
        //     return false;
        // }
        $return = $businessLocation->save();

        return $return;
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
    protected function loadAttributes(BusinessLocation $businessLocation)
    {
        $businessLocation->setAttributes($this->attributes);
    }

    public function setData($business_location)
    {
        $data = $business_location['business-location'];
        
        $this->user_id = Yii::$app->user->id;
        $this->business_name = $data['business_name'];
        $this->location_name = $data['location_name'];
        $this->address = $data['address'];
        $this->city = $data['city'];
        $this->state = $data['state'];
        $this->zip = $data['zip'];
        $this->phone = $data['phone'];
        $this->google_page = $data['google_page'];
        $this->facebook_page = $data['facebook_page'];
        $this->trip_advisor = $data['trip_advisor'];
        $this->yelp = $data['yelp'];
        $this->google_place_id = $data['google_place_id'];
        $this->feedback_approach_text = $data['feedback_approach_text'];

        //var_dump($businessLocation);exit;
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
