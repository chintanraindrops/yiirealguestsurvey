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

/**
 * Database fields:
 * @property integer $id
 * @property integer $location_id
 * @property string  $firstname
 * @property string  $lastname
 * @property string  $email
 * @property string  $status
 * @property integer $mobile
 * @property integer $created_at
 *
 * Defined relations:
 * @property Account[] $accounts
 * @property Profile   $profile
 *
 * Dependencies:
 * @property-read Finder $finder
 * @property-read Module $module
 * @property-read Mailer $mailer
 *
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class RequestFeedback extends ActiveRecord // implements IdentityInterface
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
     * @var string firstname
     */
    public $firstname;
    /**
     * @var string lastname
     */
    public $lastname;
    /**
     * @var string email
     */
    public $email;
    /**
     * @var integer mobile
     */
    public $mobile;
    /**
     * @var integer created_at
     */
    public $created_at;
    /**
     * @var string status
     */
    public $status;
    /**
     * @var string token
     */
    public $token;


    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'location_id'          => \Yii::t('user', 'Location'),
            'firstname'          => \Yii::t('user', 'Firstname'),
            'lastname'          => \Yii::t('user', 'Lastname'),
            'email'             => \Yii::t('user', 'Email'),
            'mobile'          => \Yii::t('user', 'Mobile'),
            'created_at'        => \Yii::t('user', 'Created time'),
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            'request' => ['location_id', 'firstname', 'lastname', 'email', 'mobile'],
        ]);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'locationRequired'   => ['location_id','required'],
            // firstname rules
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
        ];
    }


    public function request()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        // $transaction = $this->getDb()->beginTransaction();
        try {
            //$this->confirmed_at = $this->module->enableConfirmation ? null : time();
            //$this->password     = $this->module->enableGeneratingPassword ? Password::generate(8) : $this->password;

            //$this->trigger(self::BEFORE_REGISTER);
            //echo '<pre>';print_r($this);exit;
            // set dynamic attribs....
            $this->user_id = \Yii::$app->user->id;
            //$this->token = 'Ddsfdf654654DERDFGFCXG';
            $this->token = \Yii::$app->security->generateRandomString();
            $this->created_at = time();
            $this->status = "";
            $this->loadAttributes($this);
            if((!empty($this->firstname) && !empty($this->lastname) && !empty($this->email)) || !empty($this->mobile)){
                $this->setAttribute("location_id", $this->location_id);
                $this->setAttribute("user_id", \Yii::$app->user->id);
                $this->setAttribute("token", $this->token);
                $this->setAttribute("created_at", time());
                $this->setAttribute("status", "sent");
                $this->setAttribute("firstname", $this->firstname);
                $this->setAttribute("lastname", $this->lastname);
                $this->setAttribute("email", $this->email);
                $this->setAttribute("mobile", $this->mobile);
                $this->setAttribute("token", $this->token);
                if (!$this->save()) {
                    // $transaction->rollBack();
                    return false;
                } else {
                   if(!empty($this->email)){
                    /*var_dump($this);exit;
                       $mailsent = $this->mailer->sendWelcomeMessage($this);
                       var_dump($mailsent);exit;*/
                       return true;
                   } else {
                    return false;
                   }
                }
            } else {
                return false;
            }
            // echo "<pre>"; print_r($this); exit;
                

            // if ($this->module->enableConfirmation) {
            //     /** @var Token $token */
            //     $token = \Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_CONFIRMATION]);
            //     $token->link('user', $this);
            // }

            // $this->mailer->sendWelcomeMessage($this, isset($token) ? $token : null);
            // $this->trigger(self::AFTER_REGISTER);

            // $transaction->commit();

            /*\Yii::$app->session->setFlash(
                'info',
                \Yii::t(
                    'user',
                    'Feedback Request Successfully Sent'
                )
            );*/

            return true;
        } catch (\Exception $e) {
            // echo "tesT"; exit;
            // echo "<pre>"; print_r($e); exit;
            // $transaction->rollBack();
            return false;
        }
    }


    
    /** @inheritdoc */
    public static function tableName()
    {
        //echo 'tableName';exit;
        // return '{{%feedback_request}}';
        return 'feedback_request';
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className='RequestFeedback')
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

    protected function loadAttributes(RequestFeedback $RequestFeedback)
    {   
        // echo "<pre>"; print_r($this->attributes);
        $RequestFeedback->setAttributes($this->attributes);
    }

}
