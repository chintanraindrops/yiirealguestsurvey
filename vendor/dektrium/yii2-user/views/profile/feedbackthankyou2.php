<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \dektrium\user\models\Profile $profile
 */

//var_dump($profile);exit;
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    
                </div>
                <div class="col-sm-6 col-md-8">
                    <h3>Thank you!</h3>
                    <h3>Please tell us who we can thank for the feedback.</h3>
                    <hr>
                    <?php $form = ActiveForm::begin([
                            'id'                     => 'feedbackthankyou',
                            //'enableAjaxValidation'   => true,
                            //'enableClientValidation' => false,
                        ]); ?>
                    <div class="form-group"><?= Html::textInput('from_firstname', '', ['class'=>'form-control', 'placeholder'=>'First Name']) ?></div>
                    <div class="form-group field-feedback-form-type"><?= Html::textInput('from_lastname', '', ['class'=>'form-control', 'placeholder'=>'Last Name']) ?></div>
                    <div class="form-group field-feedback-form-type"><?= Html::textInput('from_email', '', ['class'=>'form-control', 'placeholder'=>'Email']) ?></div>
                    <div class="form-group field-feedback-form-type">
                        <?= Html::submitButton('SAVE', ['class'=>'btn btn-success']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
<?php
$this->registerJsFile('http://maps.google.com/maps/api/js?sensor=false');
$this->registerJs(' 
    $(document).ready(function(){
        console.log(\'start\');
        $("#negative_feedback_btn").click(function(){
            console.log("negative clicked!!");
            $("#client_feedback_type").val("negative");
            $("#feedback_note_submit").removeClass("hidden");
        });
        $("#positive_feedback_btn").click(function(){
            console.log("positive clicked!!");
            $("#client_feedback_type").val("positive");
            $("#feedback_note_submit").removeClass("hidden");
            var clientname = jQuery("#client_name").val();
            var cityname = jQuery("#client_city").val();
            var zipcode = jQuery("#client_zip_code").val();
            var placeid = jQuery("#client_place_id").val();
            var geocoder =  new google.maps.Geocoder();
            if(placeid !== ""){
                //window.location.href="http://search.google.com/local/writereview?placeid="+placeid;
            } else {
                console.log("Nothing to search from...");
            }
        });
        
});', \yii\web\View::POS_READY);
?>