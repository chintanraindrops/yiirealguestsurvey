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
class RequestContactForm extends Model
{
    use ModuleTrait;
    
    /**
     * @var string phone
     */
    public $phone;

    /**
     * @var string website
     */
    public $website;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'websiteLength'   => ['website', 'string', 'min' => 3, 'max' => 255],
            'websiteTrim'     => ['website', 'filter', 'filter' => 'trim'],
            'websiteUrl' => ['website', 'url'],
            'phoneRequired' => ['phone', 'required'],
            'phoneLength'   => ['phone', 'string', 'min' => 10, 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('user', 'Contact Phone'),
            'website'    => Yii::t('user', 'Website'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'request-contact-form';
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
        $username = \Yii::$app->user->identity->getUsername();
        $password = \Yii::$app->user->identity->getPassword();
        $phone = $this->phone;
        $website = $this->website;
        // $email = "chintan@raindropsinfotech.com";
        if($this->mailer->sendAccountsetupMessage($username, $password, $phone, $website)){
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
    // protected function loadAttributes(RequestFeedback $requestfeedback)
    // {
    //     // echo "<pre>"; print_r($_REQUEST);
    //     // echo "<pre>"; print_r($this->attributes); exit;
    //     // $this->attributes['role'] = $_REQUEST['register-form']['role'];
    //     $requestfeedback->setAttributes($this->attributes);
    // }
}
