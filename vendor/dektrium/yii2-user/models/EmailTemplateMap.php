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
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * 
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class EmailTemplateMap extends ActiveRecord // implements IdentityInterface
{
    use ModuleTrait;

    /**
     * @var integer id
     */
    public $id;
    /**
     * @var integer user_id
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
     * @var integer template_id
     */
    public $template_id;
    /**
     * @var integer active
     */
    public $active;

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('user', 'Id'),
            'user_id' => \Yii::t('user', 'User'),
            'title' => \Yii::t('user', 'Title'),
            'template' => \Yii::t('user', 'Email Template'),
            'template_id' => \Yii::t('user', 'Email Template ID'),
            'active' => \Yii::t('user', 'Is Active?'),
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
            'userIdRequired' => ['user_id', 'required'],
            'titleRequired' => ['title', 'required'],
            'templateRequired' => ['template', 'required'],
            'templateIdRequired' => ['template_id', 'required'],
        ];
    }


    public function add()
    {
        if (!$this->validate()) {
            return false;
        }
        $this->loadAttributes($this);
        $this->setAttribute("user_id", $this->user_id);
        $this->setAttribute("title", $this->title);
        $this->setAttribute("template", $this->template);
        $this->setAttribute("template_id", $this->template_id);
        $this->setAttribute("active", ($this->active) ? 1 : 0);

        $data = array();
        $data['title'] = $this->title;
        $data['template'] = $this->template;
        $data['template_id'] = $this->template_id;
        $data['active'] = ($this->active) ? 1 : 0;

        $email_template = self::find()->where(['user_id' => \Yii::$app->user->id, 'template_id'=>$this->template_id])->all();
        if(!empty($email_template)){
            return self::updateAll($data, ['id' => $email_template->getAttribute("id")]);
        }
        
        return $this->save();
    }

    
    /** @inheritdoc */
    public static function tableName()
    {
        return 'email_template_map';
    }

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className='EmailTemplateMap')
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

    protected function loadAttributes(EmailTemplateMap $emailTemplateMap)
    {   
        $emailTemplateMap->setAttributes($this->attributes);
    }

}
