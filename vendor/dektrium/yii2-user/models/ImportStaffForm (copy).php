<?php

namespace dektrium\user\models;

use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Raindrops Infotech <raindropsinfotech@gmail.com>
 */
class ImportStaffForm extends Model
{
    use ModuleTrait;
    
    /**
     * @var UploadedFile
     */
    public $csvfile;

    /**
     * FGETCSV() options: length, delimiter, enclosure, escape.
     * @var array 
     */
    public $fgetcsvOptions = ['length' => 0, 'delimiter' => ',', 'enclosure' => '"', 'escape' => "\\"];

    /**
     * Start insert from line number. Set 1 if CSV file has header.
     * @var integer
     */
    public $startFromLine = 1;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['csvfile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv'],
        ];
    }

    public function upload()
    {
        $this->csvfile = $this->csvfile[0];
        $members = $this->readFile();
        foreach ($members as $member) {
            $user = \Yii::createObject(User::className());
            $user->username = $member[0];
            $user->email = $member[1];
            $user->password = $member[2];
            $user->role = $member[3];

            $user->register();

            $userMap = \Yii::createObject(UserMap::className());
            $userMap->user_id = $user->id;
            $userMap->parent_id = \Yii::$app->user->id;

            $userMap->add();

            Yii::$app->session->setFlash(
                'info',
                Yii::t(
                    'user',
                    'Staff-members addedd successfully.'
                )
            );

        }

        // if ($this->validate()) {
        //     $this->csvfile->saveAs('uploads/' . $this->csvfiles->baseName . '.' . $this->csvfiles->extension);
        //     $this->readFile();
        //     return true;
        // } else {
        //     return false;
        // }
    }

    public function readFile() {
        //Prepare fgetcsv parameters
        $length = isset($this->fgetcsvOptions['length']) ? $this->fgetcsvOptions['length'] : 0;
        $delimiter = isset($this->fgetcsvOptions['delimiter']) ? $this->fgetcsvOptions['delimiter'] : ',';
        $enclosure = isset($this->fgetcsvOptions['enclosure']) ? $this->fgetcsvOptions['enclosure'] : '"';
        $escape = isset($this->fgetcsvOptions['escape']) ? $this->fgetcsvOptions['escape'] : "\\";
        $lines = []; //Clear and set rows
        if (($fp = fopen($this->csvfile->tempName, 'r')) !== FALSE) {
            while (($line = fgetcsv($fp, $length, $delimiter, $enclosure, $escape)) !== FALSE) {
                array_push($lines, $line);
            }
        }
        //Remove unused lines from all lines
        for ($i = 0; $i < $this->startFromLine; $i++) {
            unset($lines[$i]);
        }

        return $lines;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'csvfile' => Yii::t('user', 'Select (.csv) File'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'import-staff-form';
    }

    // /**
    //  * Registers a new user account. If registration was successful it will set flash message.
    //  *
    //  * @return bool
    //  */
    // public function request()
    // {
    //     if (!$this->validate()) {
    //         return false;
    //     }

    //     $importStaff = Yii::createObject(ImportStaff::className());
    //     //var_dump($importStaff);exit;
    //     $importStaff->setScenario('request');
    //     $this->loadAttributes($importStaff);

    //     //var_dump($importStaff);exit;

    //     if (!$importStaff->request()) {
    //          return false;
    //     }

    //     Yii::$app->session->setFlash(
    //         'info',
    //         Yii::t(
    //             'user',
    //             'Staff Members imported successfully.'
    //         )
    //     );

    //     return true;
    // }

    // /**
    //  * Loads attributes to the user model. You should override this method if you are going to add new fields to the
    //  * registration form. You can read more in special guide.
    //  *
    //  * By default this method set all attributes of this model to the attributes of User model, so you should properly
    //  * configure safe attributes of your User model.
    //  *
    //  * @param User $user
    //  */
    // protected function loadAttributes(ImportStaff $importStaff)
    // {
    //     // echo "<pre>"; print_r($_REQUEST);
    //     // echo "<pre>"; print_r($this->attributes); exit;
    //     // $this->attributes['role'] = $_REQUEST['register-form']['role'];
    //     $importStaff->setAttributes($this->attributes);
    // }
}
