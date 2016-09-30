<?php 
/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\widgets\Connect;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View                   $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module           $module
 */

$this->title = Yii::t('user', 'Account Setup');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="request contact">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-sx-12">
			<div class="row row-centered">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<p class="text-center text-primary">Do you want our team to setup your account? If so enter your phone number and we will call you when we are done.</p>

					<?php $form = ActiveForm::begin([
						'id' => 'request-contact-form',
						'options' => ['class' => 'form-inline'],
						'enableAjaxValidation'   => false,
						'enableClientValidation' => true,
						'validateOnBlur'         => true,
						// 'action' => '/index.php/user/clientDashboard/requestcontact'
						'action' => ['/user/clientDashboard/requestcontact'],
					]); ?>

					<div class="row row-centered">
						<div class="col-md-6 col-centered">
							<?= $form->field($modelContact, 'phone') ?>
						</div>

						<div class="col-md-6 col-centered">
							<?= $form->field($modelContact, 'website') ?>
						</div>
					</div>

					<div class="row row-centered">
						<div class="col-md-12 col-centered">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-primary']) ?>
							</div>
						</div>
					</div>
					<?php ActiveForm::end(); ?>
				</div>
				<div class="col-md-2"></div>
			</div>
			<hr/>
			<div class="row">
				<p class="text-primary">About Your Business</p>
				<?php $form = ActiveForm::begin([
					'id' => 'profile-form',
					'options' => ['class' => 'form profile'],
					'enableAjaxValidation'   => false,
					'enableClientValidation' => true,
					'validateOnBlur'         => true,
					// 'action' => '/index.php/user/clientDashboard/profilesetup'
					'action' => ['/user/clientDashboard/profilesetup'],
				]); ?>
					<div class="col-md-6">
						<?= $form->field($modelProfile, 'name') ?>
						<?= $form->field($modelProfile, 'business_name') ?>
						<?= $form->field($modelProfile, 'website') ?>
					</div>
					<div class="col-md-6">
						<?= $form->field($modelProfile, 'address') ?>
						<div class="city-state">
							<div class="col-md-6">
								<?= $form->field($modelProfile, 'city') ?>
							</div>
							<div class="col-md-6">
								<?= $form->field($modelProfile, 'state') ?>
							</div>
						</div>
						<?= $form->field($modelProfile, 'zip') ?>
						<?= $form->field($modelProfile, 'phone') ?>
						<?= Html::activeHiddenInput($modelProfile, 'google_page') ?>
						<?= Html::activeHiddenInput($modelProfile, 'facebook_page') ?>
						<?= Html::activeHiddenInput($modelProfile, 'trip_advisor') ?>
						<?= Html::activeHiddenInput($modelProfile, 'yelp') ?>
						<?= Html::activeHiddenInput($modelProfile, 'setup', ['value'=>'step1']) ?>
						
						<div class="form-group button">
							<label>How many locations?</label><br/>
							1<input type="radio" name="no_of_location" value="1" checked="checked" />&nbsp;&nbsp;&nbsp;
							2 or more<input type="radio" name="no_of_location" value="2" />
						</div>

						<div class="form-group button">
							<?= Html::submitButton(Yii::t('user', 'Continue'), ['class' => 'btn btn-primary']) ?>
						</div>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	.row-centered {
		text-align:center;
	}
	.col-centered {
		display:inline-block;
		float:none;
		margin-right:-4px;
	}
	.profile .control-label{
		float: left;
		width: 30%;
		text-align: right;
		height: 34px;
		line-height: 1.42857;
    	padding: 6px 12px;
	}
	.control-label:after{
		content: ':';
	}
	.profile .form-control{
		width: 70%;
	}
	.form-group.button {
	    float: right;
	    width: 70%;
	}
	.city-state {
		float: right;
		width: 84.5%;
	}
	.city-state .col-md-6{
		padding-right: 0;
	}
</style>