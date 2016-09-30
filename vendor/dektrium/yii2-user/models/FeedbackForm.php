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
class FeedbackForm extends Model
{
    use ModuleTrait;
    
    /**
     * @var int User Id
     */
    public $user_id;

    /**
     * @var string Type
     */
    public $type;

    /**
     * @var string From Firstname
     */
    public $from_firstname;

    /**
     * @var string From Lastname
     */
    public $from_lastname;

    /**
     * @var string From Email
     */
    public $from_email;

    /**
     * @var string From Mobile
     */
    public $from_mobile;

    /**
     * @var string Notes
     */
    public $notes;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['notes'], 'string'],
            [['type', 'from_firstname', 'from_lastname', 'from_email', 'from_mobile'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => '', 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'from_firstname'    => Yii::t('user', 'From Firstname'),
            'from_lastname' => Yii::t('user', 'From Lastname'),
            'from_email' => Yii::t('user', 'From Email'),
            'from_mobile' => Yii::t('user', 'From Mobile'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'feedback-form';
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
    public function feedback()
    {
        if (!$this->validate()) {
            return false;
        }

        $feedback = Yii::createObject(Feedback::className());
        var_dump($feedback);exit;
        $feedback->setScenario('feedback');
        $this->loadAttributes($feedback);

        //var_dump($requestfeedback);exit;

        if (!$feedback->feedback()) {
             return false;
        }

        //$mailsent = $this->mailer->sendFeedbackMessage($this);
        
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
                    'Feedback Request Saved but SMS functionality not working'
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
    protected function loadAttributes(Feedback $feedback)
    {
        // echo "<pre>"; print_r($_REQUEST);
        // echo "<pre>"; print_r($this->attributes); exit;
        // $this->attributes['role'] = $_REQUEST['register-form']['role'];
        $feedback->setAttributes($this->attributes);
    }
}
