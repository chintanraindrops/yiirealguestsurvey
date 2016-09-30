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

//var_dump($location);exit;

$profile_title = empty($profile->business_name) ? $profile->user->username : $profile->business_name;
$this->title = empty($location->getAttribute('location_name')) ? $profile_title : $location->getAttribute('location_name');
$profile_feedback_text = (!empty($profile->feedback_approach_text)) ? $profile->feedback_approach_text : '';
$location_feedback_text = (!empty($location->getAttribute('feedback_approach_text'))) ? $location->getAttribute('feedback_approach_text') : $profile_feedback_text;
if(!empty($this->title)){
    $this->params['breadcrumbs'][] = $this->title;
    $request = Yii::$app->request;
    /*$get = $request->get();
    $id = (!empty($get['id'])) ? $get['id'] : $profile->user_id;
    $guest_firstname = (!empty($get['firstname'])) ? $get['firstname'] : '';
    $guest_lastname = (!empty($get['lastname'])) ? $get['lastname'] : '';
    $guest_email = (!empty($get['email'])) ? $get['email'] : '';
    $guest_mobile = (!empty($get['mobile'])) ? $get['mobile'] : '';*/
    if(isset($requestFeedbackData)){
        $id = (!empty($requestFeedbackData->getAttribute('user_id'))) ? $requestFeedbackData->getAttribute('user_id') : $profile->user_id;
        $guest_location_id = (!empty($requestFeedbackData->getAttribute('location_id'))) ? $requestFeedbackData->getAttribute('location_id') : '';
        $guest_firstname = (!empty($requestFeedbackData->getAttribute('firstname'))) ? $requestFeedbackData->getAttribute('firstname') : '';
        $guest_lastname = (!empty($requestFeedbackData->getAttribute('lastname'))) ? $requestFeedbackData->getAttribute('lastname') : '';
        $guest_email = (!empty($requestFeedbackData->getAttribute('email'))) ? $requestFeedbackData->getAttribute('email') : '';
        $guest_mobile = (!empty($requestFeedbackData->getAttribute('mobile'))) ? $requestFeedbackData->getAttribute('mobile') : '';
        $guest_token = (!empty($requestFeedbackData->getAttribute('token'))) ? $requestFeedbackData->getAttribute('token') : '';
    } else {
        $id = $profile->user_id;
        $guest_location_id = $location->getAttribute('id');
        $guest_firstname = Null;
        $guest_lastname = Null;
        $guest_email = Null;
        $guest_mobile = '';
        $guest_token = \Yii::$app->security->generateRandomString();
    }
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="row">
                <div class="col-sm-6 col-md-4 business-profile-pic">
                    <?php
                    if(!empty($location->getAttribute('logo'))){
                    ?>
                    <?= Html::img(Url::to('@web/images/logo/'.$profile->user_id.'/'.$location->getAttribute('logo')), [
                        'class' => 'img-rounded img-responsive',
                        'alt'   => $this->title,
                    ]) ?>
                    <?php    
                    } else {
                        echo '<h4>'.$this->title.'</h4>';
                    /*
                    ?>
                        <?=  Html::img($profile->getAvatarUrl(230), [
                            'class' => 'img-rounded img-responsive',
                            'alt'   => $profile->user->username,
                        ]) ?>
                    <?php
                    */
                    }
                    ?>
                </div>
                <div class="col-sm-6 col-md-8">
                    <h4><?= $this->title ?></h4>
                    <p>Hello <?php if(!empty($guest_firstname)){ echo $guest_firstname.','; } ?></p>
                    <p>Please take one minute to complete this quick survey.</p>
                    <p style="color:#014284;" id="feedback_approach_text" class="feedback-approach-text"><?= $location_feedback_text ?></p>
                    <hr>
                    <h3>Would You Recommend <?= ucwords($this->title) ?>?</h3>
                    <div class="form-group field-feedback-form-type">
                    <img src="<?php echo Url::to('@web/images/yes_off.png'); ?>" width="130" height="75" alt="Click here if you are satisfied with our service" class="btn-feedback btn-feedback-no" id="positive_feedback_off_btn" />
                    <img src="<?php echo Url::to('@web/images/yes_on.png'); ?>" width="130" height="75" alt="Click here if you are satisfied with our service" class="btn-feedback btn-feedback-no" id="positive_feedback_on_btn" style="display:none;" />
                    <img src="<?php echo Url::to('@web/images/no_off.png'); ?>" width="130" height="75" alt="Click here if you are not satisfied with our service" class="btn-feedback btn-feedback-yes" id="negative_feedback_off_btn" />
                    <img src="<?php echo Url::to('@web/images/no_on.png'); ?>" width="130" height="75" alt="Click here if you are not satisfied with our service" class="btn-feedback btn-feedback-yes" id="negative_feedback_on_btn" style="display:none;" />
                    <?php /* Html::img('@web/images/btn-yes.png', ['alt'=>'Click here if you are satisfied with our service', 'class'=>'btn-feedback btn-feedback-no', 'id'=> 'positive_feedback_btn']) ?>
                    <?= Html::img('@web/images/btn-no.png', ['alt'=>'Click here if you are not satisfied with our service', 'class'=>'btn-feedback btn-feedback-yes', 'id'=> 'negative_feedback_btn']) */ ?>
                    </div>
                    <div class="hidden" id="feedback_note_submit">
                        <?php $form = ActiveForm::begin([
                            'id'                     => 'feedbackform',
                            //'enableAjaxValidation'   => true,
                            //'enableClientValidation' => false,
                        ]); ?>
                        <div class="form-group field-feedback-form-note">
                            <?= Html::textarea('notes', '', ['class'=>'form-control notes', 'placeholder'=>'Tell us, why?']) ?>
                        </div>
                        <div class="form-group" id="contact_me_group">
                            <?= Html::checkbox('contact_me', false, ['id' => 'contact_me']) ?>
                            <?= Html::label('Have management contact me', 'contact_me', ['id' => 'contact_me_label']) ?>
                        </div>
                        <div class="form-group">
                            <?= Html::hiddenInput('user_id', $id) ?>
                            <?= Html::hiddenInput('location_id', $guest_location_id) ?>
                            <?= Html::hiddenInput('from_firstname', $guest_firstname) ?>
                            <?= Html::hiddenInput('from_lastname', $guest_lastname) ?>
                            <?= Html::hiddenInput('from_email', $guest_email) ?>
                            <?= Html::hiddenInput('from_mobile', $guest_mobile) ?>
                            <?= Html::hiddenInput('from_token', $guest_token) ?>
                            <?= Html::hiddenInput('client_zip_code', $profile->zip, ['id' => 'client_zip_code']) ?>
                            <?= Html::hiddenInput('client_city', $profile->city, ['id' => 'client_city']) ?>
                            <?= Html::hiddenInput('client_name', $profile->name, ['id' => 'client_name']) ?>
                            <?= Html::hiddenInput('client_place_id', $profile->place_id, ['id' => 'client_place_id']) ?>
                            <?= Html::hiddenInput('client_feedback_type', 'negative', ['id' => 'client_feedback_type']) ?>
                            <?= Html::submitButton('Send Feedback', ['class'=>'btn btn-success']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
/*
if(cityname !== ""){
    var searchfrom = cityname;
} else if(zipcode !== ""){
    var searchfrom = zipcode;
} else {
    var searchfrom = "";
}
console.log(searchfrom);
if(searchfrom !== "" && clientname !== ""){
    geocoder.geocode( { "address": searchfrom}, function(results, status) { //miami, us
        if (status == google.maps.GeocoderStatus.OK) {
            if(results[0].geometry.location.lat() !== ""){
                jQuery("#jform_latitude").val(results[0].geometry.location.lat());
            }
            if(results[0].geometry.location.lng() !== ""){
                jQuery("#jform_longitude").val(results[0].geometry.location.lng());
            }
            console.log("location : " + results[0].geometry.location.lat() + " " +results[0].geometry.location.lng()); 
            
            getPlaceIDUrl = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location="+results[0].geometry.location.lat()+","+results[0].geometry.location.lng()+"&radius=500&name="+encodeURIComponent(clientname)+"&key=AIzaSyDEdoQv789Hh-UqnfjFiA9wLOwmcBq8J-Y";
            console.log(getPlaceIDUrl);
            $.ajax({
              url: getPlaceIDUrl,
              context: document.body
            }).done(function() {
              $( this ).addClass( "done" );
            });
        } else {
            console.log("Something got wrong " + status);
        }
    });
} else {
    console.log("Nothing to search from...");
}
*/
//http://search.google.com/local/writereview?placeid=
?>
<?php
$this->registerJsFile('http://maps.google.com/maps/api/js?sensor=false');
$this->registerJs(' 
    $(document).ready(function(){
        console.log(\'start\');
        $("#negative_feedback_on_btn").hide();
        $("#positive_feedback_on_btn").hide();
        $("#negative_feedback_off_btn").click(function(){
            console.log("negative clicked!!");
            
            $("#negative_feedback_off_btn").hide();
            $("#negative_feedback_on_btn").show();
            $("#positive_feedback_off_btn").show();
            $("#positive_feedback_on_btn").hide();
            
            $("#client_feedback_type").val("negative");
            $("#feedback_note_submit").removeClass("hidden");
            $("#contact_me_group").show();

        });
        $("#positive_feedback_off_btn").click(function(){
            console.log("positive clicked!!");

            $("#negative_feedback_off_btn").show();
            $("#negative_feedback_on_btn").hide();
            $("#positive_feedback_off_btn").hide();
            $("#positive_feedback_on_btn").show();

            $("#client_feedback_type").val("positive");
            $("#feedback_note_submit").removeClass("hidden");
            $("#contact_me_group").hide();

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