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

use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use dektrium\user\models\UserMap;
use dektrium\user\models\EmailTemplateMap;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RegistrationForm extends Model
{
    use ModuleTrait;
    /**
     * @var string User email address
     */
    public $email;

    /**
     * @var string Username
     */
    public $username;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var string Role
     */
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $user = $this->module->modelMap['User'];

        return [
            // username rules
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 255],
            'usernameTrim'     => ['username', 'filter', 'filter' => 'trim'],
            'usernamePattern'  => ['username', 'match', 'pattern' => $user::$usernameRegexp],
            'usernameRequired' => ['username', 'required'],
            'usernameUnique'   => [
                'username',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('user', 'This username has already been taken')
            ],
            // email rules
            'emailTrim'     => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern'  => ['email', 'email'],
            'emailUnique'   => [
                'email',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('user', 'This email address has already been taken')
            ],
            // password rules
            'passwordRequired' => ['password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            'passwordLength'   => ['password', 'string', 'min' => 6, 'max' => 72],

            // CUSTOM NIKHIL...
            // role rule
            'roleRequired' => ['role', 'required', 'on' => ['register']],
            // CUSTOM NIKHIL...
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'    => Yii::t('user', 'Email'),
            'username' => Yii::t('user', 'Username'),
            'password' => Yii::t('user', 'Password'),

            // CUSTOM NIKHIL...
            'role' => Yii::t('user', 'role'),
            // CUSTOM NIKHIL...
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'register-form';
    }

    /**
     * Registers a new user account. If registration was successful it will set flash message.
     *
     * @return bool
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */

        $user = Yii::createObject(User::className());
        $user->setScenario('register');
        $this->loadAttributes($user);

        // CUSTOM NIKHIL...
            $user->role = $_REQUEST['register-form']['role'];
        // CUSTOM NIKHIL...

        if (!$user->register()) {
            return false;
        }

        // $user->userkey = \Yii::$app->security->encryptByPassword($user->password, $user->getAuthKey());
        $user->userkey = base64_encode($user->password);
        $user->save();

        // set email template for user...
        // $emailTemplate = \Yii::createObject(EmailTemplateMap::className());
        // $emailTemplate->user_id = $user->id;
        // $emailTemplate->title = 'default';
        // $emailTemplate->template = 'Hello {customer},
        // This is a default Email Template.';
        // $emailTemplate->add();

        if($user->role == "client_staff"){
            // get current login user id...
            $parent_id = \Yii::$app->user->id;

            $userMap = \Yii::createObject(UserMap::className());
            $userMap->user_id=$user->id;
            $userMap->parent_id=$parent_id;

            $userMap->add();

            Yii::$app->session->setFlash(
                'info',
                Yii::t(
                    'user',
                    'Staff-member addedd successfully.'
                )
            );

            return true;
        }

        Yii::$app->session->setFlash(
            'info',
            Yii::t(
                'user',
                'Your account has been created and a message with further instructions has been sent to your email'
            )
        );

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
    protected function loadAttributes(User $user)
    {
        // echo "<pre>"; print_r($_REQUEST);
        // echo "<pre>"; print_r($this->attributes); exit;
        // $this->attributes['role'] = $_REQUEST['register-form']['role'];
        $user->setAttributes($this->attributes);
    }
}
