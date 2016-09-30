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
use yii\helpers\Url;

/**
 * @var yii\web\View                   $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module           $module
 */

// $this->title = Yii::t('user', 'Edit Location');
$this->title = $locationForm->business_name." (".$locationForm->location_name.")";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="businessLocation row">
	<div class="col-md-3">
        <?= $this->render('_menu', [
            'business_name' => $business_name,
        ]) ?>
    </div>
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading"> Edit Location </div>
			<div class="panel-body">
				<?php $form = ActiveForm::begin([
						'id' => 'business-location',
						'options' => ['class' => 'form profile', 'enctype' => 'multipart/form-data'],
						// 'enableAjaxValidation'   => true,
						'enableClientValidation' => true,
						'validateOnBlur'         => true,
						// 'action' => 'index.php?r=user/businessLocation/update'
						'action' => ['/user/businessLocation/update']
					]); ?>

					<?= Html::activeHiddenInput($locationForm, 'id') ?>
					<?= Html::activeHiddenInput($locationForm, 'business_name') ?>
			
					<?= $form->field($locationForm, 'location_name') ?>
					<?= $form->field($locationForm, 'address') ?>
					<?= $form->field($locationForm, 'city') ?>
					<?= $form->field($locationForm, 'state') ?>
					<?= $form->field($locationForm, 'zip') ?>
					<?= $form->field($locationForm, 'phone') ?>
					<?= $form->field($locationForm, 'google_page') ?>
					<?= $form->field($locationForm, 'facebook_page') ?>
					<?= $form->field($locationForm, 'trip_advisor') ?>
					<?= $form->field($locationForm, 'yelp') ?>
					<?= $form->field($locationForm, 'google_place_id') ?>
					<?= $form->field($locationForm, 'feedback_approach_text')->textarea() ?>
					<?= $form->field($locationForm, 'profile_url') ?>
					<?= $form->field($locationForm, 'logo')->fileInput() ?>
                <?php 
                //var_dump($locationForm);
                if(!empty($locationForm->logo)) { ?>
                <div class="form-group field-profile-logo">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-9">
                    <img src="<?php echo Url::to('@web/images/logo/'.$locationForm->user_id.'/'.$locationForm->logo); ?>" alt="Company Logo" class="company-logo-img" />        
                    </div>
                    <div class="col-sm-offset-3 col-lg-9"><div class="help-block"></div>
                    </div>
                </div>
                <?php } ?>

					<div class="form-group">
						<?= Html::submitButton(Yii::t('user', 'Update Location'), ['class' => 'btn btn-primary']) ?>
						&nbsp;&nbsp;
						<!-- <a href="index.php?r=user/businessLocation/locations" class="btn btn-default">Cancel</a> -->
						<a href="<?= Url::to(['/user/businessLocation/locations']) ?>" class="btn btn-default">Cancel</a>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>