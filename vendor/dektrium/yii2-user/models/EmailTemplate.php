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
 * @property integer  $user_id
 * @property string  $title
 * @property string  $template
 *
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class EmailTemplate extends ActiveRecord // implements IdentityInterface
{
    use ModuleTrait;

    /**
     * @var string title
     */
    public $title;
    /**
     * @var string template
     */
    public $template;
    /**
     * @var string icon
     */
    public $icon;

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'title' => \Yii::t('user', 'Title'),
            'template' => \Yii::t('user', 'Email Template'),
            'icon' => \Yii::t('user', 'Icon'),
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
        return [
            'titleRequired' => ['title', 'required'],
            'templateRequired' => ['template', 'required'],
            'iconRequired' => ['icon', 'required'],
        ];
    }

    
    /** @inheritdoc */
    public static function tableName()
    {
        return 'email_template';
    }

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className='EmailTemplate')
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

    protected function loadAttributes(EmailTemplate $emailTemplate)
    {   
        $emailTemplate->setAttributes($this->attributes);
    }

}
