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

$this->title = Yii::t('user', 'Add Staff');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dashboard client add_staff">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-sx-12">
			<div id="addStaff">
				<?php if($role == "client_admin"){ ?>
					<h3>Add Staff Member</h3>
					<?php $form = ActiveForm::begin(['id' => 'form-signup', 
						// 'action' => 'index.php?r=/user/clientDashboard/addstaff',
						'action' => ['/user/clientDashboard/addstaff'],
					]); ?>
						<?= $form->field($modelProfile, 'name')->textInput(['autofocus' => true]) ?>
		                <?= $form->field($registrationModel, 'username') ?>
		                <?= $form->field($registrationModel, 'email') ?>
		                <?= $form->field($registrationModel, 'password')->passwordInput() ?>
	                    <!-- from frontend, add staff member, it should be client_staff always -->
	                    <?= Html::activeHiddenInput($registrationModel, 'role', ['value' => 'client_staff']) ?>
		                <div class="form-group">
		                    <?= Html::submitButton('Add Member', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
		                    <a href="<?= Url::to(['/user/clientDashboard/managestaff']) ?>" class="btn btn-default">Back</a>
		                </div>
		            <?php ActiveForm::end(); ?>
		        <?php } else { ?>
		        	<h3>Add Customer</h3>
					<?php $form = ActiveForm::begin(['id' => 'form-end-user', 
						'action' => ['/user/clientDashboard/addenduser'],]); ?>
						<?= $form->field($RequestFeedbackModel, 'location_id')->dropdownlist($locations) ?>
		                <?= $form->field($RequestFeedbackModel, 'firstname')->textInput(['autofocus' => true]) ?>
		                <?= $form->field($RequestFeedbackModel, 'lastname') ?>
		                <?= $form->field($RequestFeedbackModel, 'email') ?>
		                <?= $form->field($RequestFeedbackModel, 'mobile') ?>
		                <div class="form-group">
		                    <?= Html::submitButton('Add User', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
		                </div>
		            <?php ActiveForm::end(); ?>
		        <?php } ?>
			</div>
		</div>
	</div>
</div>