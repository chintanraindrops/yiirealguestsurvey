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

// $this->title = Yii::t('user', 'Add Location');
$this->title = $business_name;
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
			<div class="panel-heading"><?= Yii::t('user', 'Add Location') ?></div>
			<div class="panel-body">
				<?php $form = ActiveForm::begin([
						'id' => 'business-location',
						'options' => ['class' => 'form profile'],
						'enableAjaxValidation'   => false,
						'enableClientValidation' => true,
						'validateOnBlur'         => true,
						// 'action' => 'index.php?r=user/businessLocation/add'
						'action' => ['/user/businessLocation/add']
					]); ?>
			
					<?= Html::activeHiddenInput($locationForm, 'business_name', ['value' => $business_name]) ?>
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

					<div class="form-group">
						<?= Html::submitButton(Yii::t('user', 'Add Location'), ['class' => 'btn btn-primary']) ?>
						&nbsp;&nbsp;
						<!-- <a href="index.php?r=user/businessLocation/locations" class="btn btn-default">Cancel</a> -->
						<a href="<?= Url::to(['/user/businessLocation/locations']) ?>" class="btn btn-default"><?= Yii::t('user', 'Cancel') ?></a>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>