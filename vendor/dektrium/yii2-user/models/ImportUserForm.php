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
class ImportUserForm extends Model
{
    use ModuleTrait;
    
    /**
     * @var UploadedFile
     */
    public $csvfile;

    /**
     * @var integer
     */
    public $location_id;

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
            'locationIdRequired' => ['location_id', 'required'],
        ];
    }

    public function upload($location_id = 0)
    {
        $this->csvfile = $this->csvfile[0];
        $users = $this->readFile();
        $count = 0;
        $not_add = 0;
        $return = true;
        foreach ($users as $user) {
            $requestFeedback = \Yii::createObject(RequestFeedbackForm::className());
            // $requestFeedback->user_id = \Yii::$app->user->id;
            $requestFeedback->firstname = $user[0];
            $requestFeedback->lastname = $user[1];
            $requestFeedback->email = $user[2];
            $requestFeedback->mobile = $user[3];
            $requestFeedback->location_id = $location_id;
            // $requestFeedback->created_at = time();
            if(!empty($user[2]) || !empty($user[3])){
                $return = $requestFeedback->request();
                $count++;
            } else {
                $not_add++;
            }
        }
        Yii::$app->session->setFlash(
            'info',
            Yii::t(
                'user',
                'Feedback Request Successfully Sent to '.(($count > 1) ? $count.' Customers.' : $count.' Customer.')
            )
        );
        if($not_add > 0){
            Yii::$app->session->setFlash(
                'warning',
                Yii::t(
                    'user',
                    ($not_add > 1) ? $not_add.' Customers has no email address or mobile number.' : $not_add.' Customer has no email address or mobile number.'
                )
            );
        }

        return $return;
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
            'location_id' => Yii::t('user', 'Select Location'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'import-user-form';
    }
}
