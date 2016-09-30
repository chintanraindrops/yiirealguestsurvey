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
use yii\helpers\Url;
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
						// 'action' => '/index.php/user/clientDashboard/requestcontact3'
						'action' => ['/user/clientDashboard/requestcontact3'],
					]); ?>

					<?= Html::activeHiddenInput($modelProfile, 'name') ?>
					<?= Html::activeHiddenInput($modelProfile, 'business_name') ?>
					<?= Html::activeHiddenInput($modelProfile, 'website') ?>
					<?= Html::activeHiddenInput($modelProfile, 'address') ?>
					<?= Html::activeHiddenInput($modelProfile, 'city') ?>
					<?= Html::activeHiddenInput($modelProfile, 'state') ?>
					<?= Html::activeHiddenInput($modelProfile, 'zip') ?>
					<?= Html::activeHiddenInput($modelProfile, 'phone') ?>
					<?= Html::activeHiddenInput($modelProfile, 'google_page') ?>
					<?= Html::activeHiddenInput($modelProfile, 'facebook_page') ?>
					<?= Html::activeHiddenInput($modelProfile, 'trip_advisor') ?>
					<?= Html::activeHiddenInput($modelProfile, 'yelp') ?>

					<?php foreach ($business_location as $key => $value) {
						$v = $value['business-location'];
						echo Html::activeHiddenInput($modelLocation, 'business_name[]', ['value' => $v['business_name']]);
						echo Html::activeHiddenInput($modelLocation, 'address[]', ['value' => $v['address']]);
						echo Html::activeHiddenInput($modelLocation, 'city[]', ['value' => $v['city']]);
						echo Html::activeHiddenInput($modelLocation, 'state[]', ['value' => $v['state']]);
						echo Html::activeHiddenInput($modelLocation, 'zip[]', ['value' => $v['zip']]);
						echo Html::activeHiddenInput($modelLocation, 'phone[]', ['value' => $v['phone']]);
					} ?>

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
				<p class="text-center text-primary">Setup Outgoing Email (we can do this for you).</p>
				<div class="text-center">
					<?php $form = ActiveForm::begin([
						'id' => 'email-template-form',
						'options' => ['class' => 'form'],
						'enableAjaxValidation'   => false,
						'enableClientValidation' => false,
						'validateOnBlur'         => false,
						// 'action' => '/index.php/user/clientDashboard/finalsetup'
						'action' => ['/user/clientDashboard/finalsetup'],
					]); ?>
						<?php 
							if(!empty($user_template) && !empty($user_template->getAttribute("title"))){
								$modelEmailTemplate->title = $user_template->getAttribute("title");
							} else {
								$modelEmailTemplate->title = "Default Email"; 
							}
						?>
						<div class="image-wrapper dxcvxcv">
							<!-- <input type="hidden" value="Default Email" name="email-template-form[title]" id="title_hidden"> -->
							<?php foreach ($email_template as $key => $email) { ?>
								<?php 
									$template_id = $email->getAttribute('id');
									$template_title = $email->getAttribute('title');
									$template_icon = $email->getAttribute("icon");
									$img_src = (($template_title == 'Default Email') ? 'active' : '').$template_icon;
								?>
								<div class="image <?php echo ($template_title == 'Default Email') ? 'active' : '' ?>" id="image_<?php echo $template_id; ?>" >
									<img 
										src="<?= Url::to('@web/images/'.$img_src); ?>"
										class="<?php echo ($template_title == 'Default Email') ? 'active' : '' ?>" />
									<label for="title_<?= $template_id ?>" ><?= $template_title ?></label>
									<?= 
										$form->field($modelEmailTemplate, 'title', ['enableLabel' => false])
											->radio(['value'=>$template_title, 'class'=>'title_radio', 'id'=>'title_'.$template_id]) 
									?>
								</div>
							<?php } ?>
							<input type="hidden" value="2" id="hid_template_id" name="template_id" />
						</div>
						<div class="clearfix"></div>
						<div class="textarea col-centered col-md-6">
							<?= $form->field($modelEmailTemplate, 'template', ['enableLabel' => false])->textarea(['value' => $email_template[1]->getAttribute('template'), 'rows'=>10]) ?>
							<div class="form-group pull-right">
								<?= Html::submitButton(Yii::t('user', 'Send'), ['class' => 'btn btn-primary']) ?>
							</div>
						</div>
						<?= Html::activeHiddenInput($modelProfile, 'setup', ['value'=>'finish']) ?>
					<?php ActiveForm::end(); ?>
				</div>

				<div class="hide" id="templates">
					<?php foreach ($email_template as $key => $email) { ?>
						<?= Html::activeHiddenInput($modelEmailTemplate, 'template', ['value' => $email->getAttribute("template"), 'id' => 'template_'.$email->getAttribute("id")]) ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	.row-centered {text-align:center; }
	.col-centered {display:inline-block; float:none; margin-right:-4px; }
	.profile .control-label{float: left; width: 38%; text-align: right; height: 34px; line-height: 1.42857; padding: 6px 12px; }
	.control-label:after{content: ':'; }
	.profile .form-control{width: 62%; }
	.form-group.button {float: right; width: 62%; }
	.city-state {float: right; width: 78.5%; }
	.city-state .col-md-6{padding-right: 0; }
	.border-bottom{border-bottom: 1px solid #eee; margin-bottom: 15px;}
	.image-wrapper{float: left; width: 100%}
	.image{display: inline-block; width: 10%;}
	.image > img {vertical-align: top; width: 50px; }
	.image > label{width: 100%; font-size: 12px; font-weight: normal;}
	.image .form-group, .image .radio{float: left; margin: 0; width: 100%;}
	.image .radio input{margin: 0; position: relative;}
	.textarea{margin-top: 20px;  width: 50%;}
	textarea.form-control{border-top: 3px solid yellow; border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-bottom: 1px solid #ccc;}
</style>