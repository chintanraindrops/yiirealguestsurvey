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
					<p class="text-center text-primary">Do you want us to finish for you? We will setup all the details and call you when we are done.</p>

					<?php $form = ActiveForm::begin([
						'id' => 'request-profile-form',
						'options' => ['class' => 'form-inline'],
						'enableAjaxValidation'   => false,
						'enableClientValidation' => false,
						'validateOnBlur'         => false,
						// 'action' => '/index.php/user/clientDashboard/requestcontact2'
						'action' => ['/user/clientDashboard/requestcontact2'],
					]); ?>

					<?= Html::activeHiddenInput($modelProfile, 'name') ?>
					<?= Html::activeHiddenInput($modelProfile, 'business_name') ?>
					<?= Html::activeHiddenInput($modelProfile, 'website') ?>
					<?= Html::activeHiddenInput($modelProfile, 'address') ?>
					<?= Html::activeHiddenInput($modelProfile, 'city') ?>
					<?= Html::activeHiddenInput($modelProfile, 'state') ?>
					<?= Html::activeHiddenInput($modelProfile, 'zip') ?>
					<?= Html::activeHiddenInput($modelProfile, 'phone') ?>

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

			<?php $form = ActiveForm::begin([
				'id' => 'location-form',
				'options' => ['class' => 'form location'],
				'enableAjaxValidation'   => false,
				'enableClientValidation' => true,
				'validateOnBlur'         => true,
				// 'action' => '/index.php/user/clientDashboard/location',
				'action' => ['/user/clientDashboard/location'],
			]); ?>
				<div id="location-0" class="row border-bottom">
					<div class="col-md-6">
						<p class="text-center">
							<input type="checkbox" id="use_info_0" class="use_info" checked="checked" data-count="0" />
							<label for="use_info_0">Use information for business to create first locations?</label>
						</p>
						<div id="first_location">
							<?= $form->field($modelLocation, 'business_name[0]') ?>
							<?= $form->field($modelLocation, 'location_name[0]') ?>
							<?= $form->field($modelLocation, 'address[0]') ?>
							<div class="city-state">
								<div class="col-md-6">
									<?= $form->field($modelLocation, 'city[0]') ?>
								</div>
								<div class="col-md-6">
									<?= $form->field($modelLocation, 'state[0]') ?>
								</div>
							</div>
							<?= $form->field($modelLocation, 'zip[0]') ?>
							<?= $form->field($modelLocation, 'phone[0]') ?>
						</div>
					</div>
					<div class="col-md-6">
						<p class="text-center">Where do you want to get reviews?</p>
						<?= $form->field($modelLocation, 'google_page[0]') ?>
						<?= $form->field($modelLocation, 'facebook_page[0]') ?>
						<?= $form->field($modelLocation, 'trip_advisor[0]') ?>
						<?= $form->field($modelLocation, 'yelp[0]') ?>
						<?= $form->field($modelLocation, 'google_place_id[0]') ?>
						<?= $form->field($modelLocation, 'feedback_approach_text[0]')->textarea() ?>
						<div class="form-group button">
							<input type="checkbox" id="add_location_0" data-count="0" class="add_location" name="add_location" <?php echo ($number_of_locations > 1) ? 'checked="checked" disabled="disabled"' : ''; ?> />
							<label for="add_location_0">Add another locations?</label>
						</div>
					</div>
				</div>
				<div id="locations">
					<?php if($number_of_locations > 1){ ?>
						<div id="location-1" class="row border-bottom">
							<div class="col-md-6">
								<?= $form->field($modelLocation, 'business_name[1]') ?>
								<?= $form->field($modelLocation, 'location_name[1]') ?>
								<?= $form->field($modelLocation, 'address[1]') ?>
								<div class="city-state">
									<div class="col-md-6">
										<?= $form->field($modelLocation, 'city[1]') ?>
									</div>
									<div class="col-md-6">
										<?= $form->field($modelLocation, 'state[1]') ?>
									</div>
								</div>
								<?= $form->field($modelLocation, 'zip[1]') ?>
								<?= $form->field($modelLocation, 'phone[1]') ?>
							</div>
							<div class="col-md-6">
								<p class="text-center">Where do you want to get reviews?</p>
								<?= $form->field($modelLocation, 'google_page[1]') ?>
								<?= $form->field($modelLocation, 'facebook_page[1]') ?>
								<?= $form->field($modelLocation, 'trip_advisor[1]') ?>
								<?= $form->field($modelLocation, 'yelp[1]') ?>
								<?= $form->field($modelLocation, 'google_place_id[1]') ?>
								<?= $form->field($modelLocation, 'feedback_approach_text[1]')->textarea() ?>
								<div class="form-group button">
									<input type="checkbox" id="add_location_1" class="add_location" data-count="1" name="add_location" />
									<label for="add_location_1">Add another locations?</label>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>

				<div class="row row-centered">
					<div class="col-md-12 col-centered">
						<div class="form-group">
							<?= Html::submitButton(Yii::t('user', 'Continue'), ['class' => 'btn btn-primary']) ?>
						</div>
					</div>
				</div>
				<?= Html::activeHiddenInput($modelProfile, 'setup', ['value'=>'step2']) ?>
				<input type="hidden" id="no_of_locations" value="<?php echo $number_of_locations; ?>" name="no_of_location" />
			<?php ActiveForm::end(); ?>
			<div class="hide" id="dummy_location">
				<div id="location-new_count" class="row border-bottom">
					<div class="col-md-6">
						<?= $form->field($modelLocation, 'business_name[new_count]') ?>
						<?= $form->field($modelLocation, 'location_name[new_count]') ?>
						<?= $form->field($modelLocation, 'address[new_count]') ?>
						<div class="city-state">
							<div class="col-md-6">
								<?= $form->field($modelLocation, 'city[new_count]') ?>
							</div>
							<div class="col-md-6">
								<?= $form->field($modelLocation, 'state[new_count]') ?>
							</div>
						</div>
						<?= $form->field($modelLocation, 'zip[new_count]') ?>
						<?= $form->field($modelLocation, 'phone[new_count]') ?>
					</div>
					<div class="col-md-6">
						<p class="text-center">Where do you want to get reviews?</p>
						<?= $form->field($modelLocation, 'google_page[new_count]') ?>
						<?= $form->field($modelLocation, 'facebook_page[new_count]') ?>
						<?= $form->field($modelLocation, 'trip_advisor[new_count]') ?>
						<?= $form->field($modelLocation, 'yelp[new_count]') ?>
						<?= $form->field($modelLocation, 'google_place_id[new_count]') ?>
						<?= $form->field($modelLocation, 'feedback_approach_text[new_count]')->textarea() ?>
						<div class="form-group button">
							<input type="checkbox" id="add_location_new_count" data-count="new_count" class="add_location" name="add_location" />
							<label for="add_location_new_count">Add another locations?</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	.row-centered {text-align:center; }
	.col-centered {display:inline-block; float:none; margin-right:-4px; }
	.location .control-label{float: left; width: 38%; text-align: right; height: 34px; line-height: 1.42857; padding: 6px 12px; }
	.control-label:after{content: ':'; }
	.location .form-control{width: 62%; }
	.form-group.button {float: right; width: 62%; }
	.city-state {float: right; width: 78.5%; }
	.city-state .col-md-6{padding-right: 0; }
	.border-bottom{border-bottom: 1px solid #eee; margin-bottom: 15px;}
</style>