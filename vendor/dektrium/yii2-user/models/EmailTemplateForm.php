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
class EmailTemplateForm extends Model
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


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'titleRequired' => ['title', 'required'],
            'templateRequired' => ['template', 'required'],
            'iconRequired' => ['icon', 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('user', 'Title'),
            'template' => Yii::t('user', 'Email Template'),
            'icon' => Yii::t('user', 'Icon'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'email-template-form';
    }
}
