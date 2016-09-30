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
$profile_title = empty($profile->business_name) ? $profile->user->username : $profile->business_name;
$this->title = empty($location->getAttribute('location_name')) ? $profile_title : $location->getAttribute('location_name');
//var_dump($profile);exit;
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
                    <?php
                    //var_dump($feedbackData);exit;
                    if($feedbackData->getAttribute('type') == 'positive'){
                        ?>
                        <h4><?= $this->title ?></h4>
                        <?php
                        if (!empty($feedbackData->getAttribute('id')) && empty($feedbackData->getAttribute('from_email'))) {
                            ?><h3>Please tell us who we can thank for the feedback.</h3><?php
                            $form = ActiveForm::begin([
                                'id'                     => 'feedbackthankyouform',
                                'enableAjaxValidation'   => true,
                                //'enableClientValidation' => false,
                            ]); ?>
                            <div class="form-group field-feedback-thankyou-form-firstname">
                                <?php /* Html::textInput('firtsname', '', ['class'=>'form-control', 'placeholder'=>'Firstname']) */ ?>
                                <?= $form->field($model, 'from_firstname') ?>
                            </div>
                            <div class="form-group field-feedback-thankyou-form-lastname">
                                <?php /* Html::textInput('lastname', '', ['class'=>'form-control', 'placeholder'=>'Lastname']) */?>
                                <?php //$form->field($model, 'from_lastname') ?>
                            </div>
                            <div class="form-group field-feedback-thankyou-form-email">
                                <?php /* Html::textInput('email', '', ['class'=>'form-control', 'placeholder'=>'Email']) */ ?>
                                <?= $form->field($model, 'from_email') ?>
                            </div>
                            <div class="form-group">
                                <?= Html::hiddenInput('feedback_id', $feedbackData->getAttribute('id'), ['id' => 'client_feedback_id']) ?>
                                <?= Html::submitButton('SAVE', ['class'=>'btn btn-success']) ?>
                            </div>
                            <?php ActiveForm::end(); ?><?php
                        } else {
                            ?>
                            <h3>Thank you <?= $feedbackData->getAttribute('from_firstname') ?>!</h3>
                            <h3>It would mean a great deal to us if you would take a second to share your experience with readers of one these sites. Thank you again.</h3>
                            <hr>
                            <div class="form-group field-feedback-form-type">
                            <img src="<?php echo Url::to('@web/images/google.jpg'); ?>" alt="Google Share" class="<?php if(!empty($location->getAttribute('google_place_id'))) { echo 'btn-feedback'; } else { echo 'hidden' ; } ?> btn-feedback-google" id="google_share_btn" style="cursor:pointer;" />
                            </div>
                            <div class="form-group field-feedback-form-type">
                            <img src="<?php echo Url::to('@web/images/tripadvisor.jpg'); ?>" alt="Trip Advisor Share" class="<?php if(!empty($location->getAttribute('trip_advisor'))) { echo 'btn-feedback'; } else { echo 'hidden' ; } ?> btn-feedback-trip" id="trip_share_btn" /></div>
                            <div class="form-group field-feedback-form-type">
                            <img src="<?php echo Url::to('@web/images/facebook.jpg'); ?>" alt="Facebook Share" class="<?php if(!empty($location->getAttribute('facebook_page'))) { echo 'btn-feedback'; } else { echo 'hidden' ; } ?> btn-feedback-facebook" id="facebook_share_btn" /></div>
                            <div class="form-group field-feedback-form-type">
                            <img src="<?php echo Url::to('@web/images/yelp.jpg'); ?>" alt="Yelp Share" class="<?php if(!empty($location->getAttribute('yelp'))) { echo 'btn-feedback'; } else { echo 'hidden' ; } ?> btn-feedback-yelp" id="yelp_share_btn" /></div>
                            <?= Html::hiddenInput('client_place_id', $location->getAttribute('google_place_id'), ['id' => 'client_place_id']) ?>
                            <?= Html::hiddenInput('client_facebook_page', $location->getAttribute('facebook_page'), ['id' => 'client_facebook_page']) ?>
                            <?= Html::hiddenInput('client_trip_advisor', $location->getAttribute('trip_advisor'), ['id' => 'client_trip_advisor']) ?>
                            <?= Html::hiddenInput('client_yelp', $location->getAttribute('yelp'), ['id' => 'client_yelp']) ?>
                            <?php
                        }
                    } else {
                        ?>
                        <h3>The management team thanks you for the honest feedback.</h3>
                        <h3>We will use your insights to improve our customer service. Management will be in contact if you so requested.</h3>
                        <?php
                        if (!empty($feedbackData->getAttribute('id')) && empty($feedbackData->getAttribute('from_email'))) {
                            $form = ActiveForm::begin([
                                'id'                     => 'feedbackthankyouform',
                                'enableAjaxValidation'   => true,
                                //'enableClientValidation' => false,
                            ]); ?>
                            <div class="form-group field-feedback-thankyou-form-firstname">
                                <?php /* Html::textInput('firtsname', '', ['class'=>'form-control', 'placeholder'=>'Firstname']) */ ?>
                                <?= $form->field($model, 'from_firstname') ?>
                            </div>
                            <div class="form-group field-feedback-thankyou-form-lastname">
                                <?php /* Html::textInput('lastname', '', ['class'=>'form-control', 'placeholder'=>'Lastname']) */?>
                                <?php // $form->field($model, 'from_lastname') ?>
                            </div>
                            <div class="form-group field-feedback-thankyou-form-email">
                                <?php /* Html::textInput('email', '', ['class'=>'form-control', 'placeholder'=>'Email']) */ ?>
                                <?= $form->field($model, 'from_email') ?>
                            </div>
                            <div class="form-group field-feedback-thankyou-form-mobile">
                                <?php /* Html::textInput('mobile', '', ['class'=>'form-control', 'placeholder'=>'Mobile']) */ ?>
                                <?= $form->field($model, 'from_mobile') ?>
                            </div>
                            <div class="form-group">
                                <?= Html::hiddenInput('feedback_id', $feedbackData->getAttribute('id'), ['id' => 'client_feedback_id']) ?>
                                <?= Html::submitButton('SAVE', ['class'=>'btn btn-success']) ?>
                            </div>
                            <?php ActiveForm::end(); ?><?php
                        }
                    }
                    ?>
                </div>
                </div>
            </div>
        </div>
    </div>
<?php
$this->registerJsFile('http://maps.google.com/maps/api/js?sensor=false');
$this->registerJs(' 
    $(document).ready(function(){
        console.log(\'start\');
        $("#google_share_btn").click(function(){
            console.log("positive clicked!!");
            var placeid = jQuery("#client_place_id").val();
            var geocoder =  new google.maps.Geocoder();
            if(placeid !== ""){
                window.location.href="http://search.google.com/local/writereview?placeid="+placeid;
            } else {
                alert("Not Valid Google Url entered from Client");
            }
        });
        $("#facebook_share_btn").click(function(){
            var facebook_reviews_url = jQuery("#client_facebook_page").val();
            console.log(facebook_reviews_url);
            if(facebook_reviews_url.match(/^(http|https)\:\/\/www.facebook.com\/.*/i)){
                window.location.href=facebook_reviews_url.replace(/\/$/, "")+"/reviews";
            } else {
                alert("Not Valid Facebook Page Url entered from Client");
            }
        });
        $("#trip_share_btn").click(function(){
            var trip_reviews_url = jQuery("#client_trip_advisor").val();
            console.log(trip_reviews_url);
            if(trip_reviews_url.match(/^(http|https)\:\/\/www.tripadvisor.*\/.*/i)){
                window.location.href=trip_reviews_url.replace(/\/$/, "")+"#REVIEWS";
            } else {
                alert("Not Valid TripAdvisor Page Url entered from Client");
            }
        });
        $("#yelp_share_btn").click(function(){
            var yelp_reviews_url = jQuery("#client_yelp").val();
            console.log(yelp_reviews_url);
            if(yelp_reviews_url.match(/^(http|https)\:\/\/www.yelp.*\/.*/i)){
                window.location.href=yelp_reviews_url;
            } else {
                alert("Not Valid Yelp Page Url entered from Client");
            }
        });
        
});', \yii\web\View::POS_READY);
?>