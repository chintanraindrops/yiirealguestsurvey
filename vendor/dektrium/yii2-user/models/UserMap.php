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
 * User Mapppig
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class UserMap extends ActiveRecord
{
	use ModuleTrait;

	/**
     * @var integer userid
     */
    public $user_id;

    /**
     * @var integer parentid
     */
    public $parent_id;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        // $userMap = $this->module->modelMap['UserMap'];

        return [
        	'userIdRequired' => ['user_id', 'required'],
            'parentIdRequired' => ['parent_id', 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id'    => Yii::t('user_map', 'User Id'),
            'parent_id' => Yii::t('user_map', 'Parent User Id'),
        ];
    }

    /**
     * map a new user account.
     *
     * @return bool
     */
    public function add()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->loadAttributes($this);
        $this->setAttribute("user_id", $this->user_id);
        $this->setAttribute("parent_id", $this->parent_id);
        return $this->save();
        // $sql = "insert into user_map (user_id, parent_id) values (30, 20)";
		// \Yii::$app->db->createCommand($sql)->execute();
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
    protected function loadAttributes(UserMap $userMap)
    {
        $userMap->setAttributes($this->attributes);
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className='UserMap')
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'user_map';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

}