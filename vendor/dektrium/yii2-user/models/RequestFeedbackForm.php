<?php

namespace dektrium\user\models;

use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use dektrium\user\Mailer;
use yii\helpers\Url;
use dektrium\user\Finder;
use dektrium\user\models\BusinessLocation;
use dektrium\user\models\UserMap;
use dektrium\user\models\EmailTemplateMap;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class RequestFeedbackForm extends Model
{
    use ModuleTrait;
    
    /**
     * @var int User Id
     */
    public $user_id;

    /**
     * @var int Location Id
     */
    public $location_id;

    /**
     * @var string Firstname
     */
    public $firstname;

    /**
     * @var string lastname
     */
    public $lastname;

    /**
     * @var string User email address
     */
    public $email;

    /**
     * @var string Mobile
     */
    public $mobile;

    /**
     * @var string Mobile
     */
    public $token;

    /** @var Finder */
    protected $finder;

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param Finder           $finder
     * @param array            $config
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // firstname rules
            'locationRequired'   => ['location_id','required'],

            'firstnameLength'   => ['firstname', 'string', 'min' => 3, 'max' => 255],
            'firstnameTrim'     => ['firstname', 'filter', 'filter' => 'trim'],
            //'firstnameRequired' => ['firstname', 'required'],
            // lastname rules
            'lastnameLength'   => ['lastname', 'string', 'min' => 3, 'max' => 255],
            'lastnameTrim'     => ['lastname', 'filter', 'filter' => 'trim'],
            //'lastnameRequired' => ['lastname', 'required'],
            // email rules
            'emailTrim'     => ['email', 'filter', 'filter' => 'trim'],
            //'emailRequired' => ['email', 'required'],
            'emailPattern'  => ['email', 'email'],
            // mobile rules
            //'mobileRequired' => ['mobile', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            'mobileLength'   => ['mobile', 'string', 'min' => 10, 'max' => 15],

            'token'   => ['token','string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'location_id'    => Yii::t('user', 'Location'),
            'firstname'    => Yii::t('user', 'Firstname'),
            'lastname' => Yii::t('user', 'Lastname'),
            'email' => Yii::t('user', 'Email'),
            'mobile' => Yii::t('user', 'Mobile'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'request-feedback-form';
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
    public function request()
    {
        if (!$this->validate()) {
            return false;
        }

        $requestfeedback = Yii::createObject(RequestFeedback::className());
        //echo '<pre>';print_r($requestfeedback);exit;
        $requestfeedback->setScenario('request');
        $this->loadAttributes($requestfeedback);
        
        if (!$requestfeedback->request()) {
             return false;
        }

        $clientObj = \Yii::$app->user->identity;
        $profileObj = $this->finder->findProfileById(\Yii::$app->user->identity->getId());
        $locationObj = BusinessLocation::find()->where(['id' => $requestfeedback->location_id])->one();

        $client_business_user_name = (!empty($profileObj->business_name)) ? $profileObj->business_name : $clientObj->username ;

        $location_business_name = (!empty($locationObj->getAttribute('location_name'))) ? $locationObj->getAttribute('location_name') : $client_business_user_name ;
        
        $data = (object) array('client_username' => $location_business_name);
        $data->from_name = $location_business_name;
        $data->from_email = $clientObj->email;
        //$data->client_username = $profileObj->business_name;
        //$url_append_data = '';
        if(!empty($this->firstname)){
            $data->firstname = $this->firstname;
            //$url_append_data .= '&firstname='.$data->firstname;
        }
        if(!empty($this->lastname)){
            $data->lastname = $this->lastname;
            //$url_append_data .= '&lastname='.$data->lastname;
        }
        if(!empty($this->email)){
            $data->email = [$this->email => $data->firstname.' '.$data->lastname];
            //$url_append_data .= '&email='.$data->email;
        }
        if(!empty($this->mobile)){
            $data->mobile = $this->mobile;
            //$url_append_data .= '&mobile='.$data->mobile;
        }
        if(!empty($requestfeedback->token)){
            $from_token = $data->token = $requestfeedback->token;
        }
        //$data->client_profile_link = Url::to('@web/index.php?r=/user/profile/feedback&id='.$clientObj->id.$url_append_data, true);
        $data->client_profile_link = Url::toRoute(['/user/profile/feedback', 'token' => $from_token], true);
        if(\Yii::$app->user->identity->role == "client_staff"){
            $userMapData = UserMap::findAll(['user_id'=>\Yii::$app->user->identity->getId()]);
            $parent_id = $userMapData[0]->getAttribute('parent_id');
            $emailTempObj = EmailTemplateMap::find()->where(['user_id'=> $parent_id, 'template_id' => 2])->one();
            if($emailTempObj !== null){
                $data->custom_message = (!empty($emailTempObj->getAttribute('template'))) ? $emailTempObj->getAttribute('template') : '';
            }
        } else {
            $emailTempObj = EmailTemplateMap::find()->where(['user_id'=> \Yii::$app->user->identity->getId(), 'template_id' => 2])->one();
            if($emailTempObj !== null){
                $data->custom_message = (!empty($emailTempObj->getAttribute('template'))) ? $emailTempObj->getAttribute('template') : '';
            }
        }
        
        $data->customer = $data->firstname.' '.$data->lastname;
        $data->manager = $profileObj->name;
        $data->business = $data->from_name;
        $data->feedback_link = $data->client_profile_link;

        //$data->
        $mailsent = $this->mailer->sendRequestFeedbackMessage($data);
        
        if(!$mailsent){
            $data->from_name = 'Real Guest User Survey';
            $data->from_email = 'chintan@raindropsinfotech.com';
            $mailsent = $this->mailer->sendRequestFeedbackMessage($data);
        }

        $this->location_id = '';
        $this->firstname = '';
        $this->lastname = '';
        $this->email = '';
        $this->mobile = '';
        
        if($mailsent){
            Yii::$app->session->setFlash(
                'info',
                Yii::t(
                    'user',
                    'Feedback Request Successfully Sent'
                )
            );
        } else {
            Yii::$app->session->setFlash(
                'info',
                Yii::t(
                    'user',
                    'Feedback Request Saved but Email not working'
                )
            );
        }

        return true;
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
    protected function loadAttributes(RequestFeedback $requestfeedback)
    {
        // echo "<pre>"; print_r($_REQUEST);
        // echo "<pre>"; print_r($this->attributes); exit;
        // $this->attributes['role'] = $_REQUEST['register-form']['role'];
        $requestfeedback->setAttributes($this->attributes);
    }
}
