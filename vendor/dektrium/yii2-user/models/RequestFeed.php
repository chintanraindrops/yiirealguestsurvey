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
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RequestFeed extends ActiveRecord // implements IdentityInterface
{
    use ModuleTrait;

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


    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
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
            'request' => ['firstname', 'lastname', 'email', 'mobile'],
        ]);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
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

            // set dynamic attribs....
            $this->user_id = \Yii::$app->user->id;
            $this->created_at = time();
            $this->status = "";
            $this->loadAttributes($this);
            $this->setAttribute("user_id", \Yii::$app->user->id);
            $this->setAttribute("created_at", time());
            $this->setAttribute("status", "");
            $this->setAttribute("firstname", $this->firstname);
            $this->setAttribute("lastname", $this->lastname);
            $this->setAttribute("email", $this->email);
            $this->setAttribute("mobile", $this->mobile);
            if (!$this->save()) {
                // $transaction->rollBack();
                return false;
            } else {
                // echo 'trueeee';
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
