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
 * Database fields:
 * @property integer $id
 * @property User  $user_id
 * @property User  $staff_id
 * @property BusinessLocation $location_id
 *
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class StaffLocationMap extends ActiveRecord
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
     * @var integer staff_id
     */
    public $staff_id;
    /**
     * @var integer location_id
     */
    public $location_id;
    
    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('user', 'Location Id'),
            'user_id' => \Yii::t('user', 'User'),
            'staff_id' => \Yii::t('user', 'Staff'),
            'location_id' => \Yii::t('user', 'Business Location'),
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
        // return [ 'required' => [['user_id'], ['staff_id'], ['location_id']], 'required'] ];
        
        return [
            'userIdRequired' => ['user_id', 'required'],
            'staffIdRequired' => ['staff_id', 'required'],
            'locationIdRequired' => ['location_id', 'required'],
        ];
    }


    public function add()
    {
        // echo "tesT"; exit;
        // if (!$this->validate()) {
        //     return false;
        // }

        $this->loadAttributes($this);
        $this->setAttribute("user_id", $this->user_id);
        $this->setAttribute("staff_id", $this->staff_id);
        $this->setAttribute("location_id", $this->location_id);

        return $this->save();
        // $sql = "insert into user_map (user_id, parent_id) values (30, 20)";
        // \Yii::$app->db->createCommand($sql)->execute();
    }


    
    /** @inheritdoc */
    public static function tableName()
    {
        return 'staff_location_map';
    }

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className='StaffLocationMap')
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

    protected function loadAttributes(StaffLocationMap $staffLocationMap)
    {
        $staffLocationMap->setAttributes($this->attributes);
    }

}
