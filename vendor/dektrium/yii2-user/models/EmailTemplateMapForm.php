<?php

namespace dektrium\user\models;

use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class EmailTemplateMapForm extends Model
{
    use ModuleTrait;
    
    /**
     * @var string user_id
     */

    public $user_id;
    /**
     * @var string title
     */
    public $title;

    /**
     * @var string template
     */
    public $template;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'userIdRequired' => ['user_id', 'required'],
            'titleRequired' => ['title', 'required'],
            'templateRequired' => ['template', 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('user', 'User'),
            'title' => Yii::t('user', 'Title'),
            'template' => Yii::t('user', 'Email Template'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'email-template-map-form';
    }
}
