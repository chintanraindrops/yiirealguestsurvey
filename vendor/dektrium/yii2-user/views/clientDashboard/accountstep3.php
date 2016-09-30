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
						<div class="col-centered col-md-8">
							<ul class="nav nav-tabs col-centered">
								<?php foreach ($email_template as $key => $email) { ?>
									<?php 
										$template_id = $email->getAttribute('id');
										$template_title = $email->getAttribute('title');
										$template_icon = $email->getAttribute("icon");
										$img_src = (($template_title == 'Default Email') ? 'active' : '').$template_icon;
									?>
									<li class="<?php echo ($template_title == 'Default Email') ? 'active' : '' ?>">
										<a data-toggle="tab" href="#template<?= $template_id ?>">
											<div id="image_<?php echo $template_id; ?>" >
												<img 
													src="<?= Url::to('@web/images/'.$img_src); ?>"
													class="<?php echo ($template_title == 'Default Email') ? 'active' : '' ?>" />
												<br/><?= $template_title ?>
											</div>
										</a>
									</li>
								<?php } ?>
							</ul>

							<div class="tab-content">
								<?php $count = 0; ?>
								<?php foreach ($email_template as $key => $email) { ?>
									<?php 
										$template_id = $email->getAttribute('id');
										$template_title = $email->getAttribute('title');
										$template_icon = $email->getAttribute("icon");
										$img_src = (($template_title == 'Default Email') ? 'active' : '').$template_icon;
									?>
									<div id="template<?= $template_id ?>" class="tab-pane fade <?php echo ($template_title == 'Default Email') ? 'in active' : '' ?>" >
										<?= Html::activeHiddenInput($modelEmailTemplate, '['.$count.']title', ['value'=>$template_title]) ?>
										<?= Html::activeHiddenInput($modelEmailTemplate, '['.$count.']template_id', ['value'=>$template_id]) ?>
										<?php if($template_id == 2) { ?>
											<p class="text-primary" id="params"> You can use any of the following parameters in your email template <br>(customer), (manager), (feedback_link), (business), {customer}, {manager}, {business}, {feedback_link}</p>
										<?php } ?>
										<?= $form->field($modelEmailTemplate, '['.$count.']template', ['enableLabel' => false])->textarea(['value' => $email_template[1]->getAttribute('template'), 'rows'=>10]) ?>
									</div>
									<?php $count++; ?>
								<?php } ?>
								<div class="form-group pull-right">
									<?= Html::submitButton(Yii::t('user', 'Send'), ['class' => 'btn btn-primary']) ?>
								</div>
							</div>
						</div>
						<?= Html::activeHiddenInput($modelProfile, 'setup', ['value'=>'finish']) ?>
					<?php ActiveForm::end(); ?>
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
	ul.nav.nav-tabs li a img {width: 45px; }
	/*.tab-content{padding: 15px 35px 15px 15px;}*/
	ul.nav.nav-tabs { border: none !important; }
	ul.nav.nav-tabs li.active a { border: none !important; }
</style>