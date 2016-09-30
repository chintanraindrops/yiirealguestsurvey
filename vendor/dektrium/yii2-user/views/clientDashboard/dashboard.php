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

if($role == "client_admin"){
	$this->title = Yii::t('user', ($activeTab == 'addStaff') ? 'Add Staff' : 'Import Feedback Request List');
	echo '<input type="hidden" id="add_staff_title" value="'.Yii::t('user', 'Add Staff').'" />';
	echo '<input type="hidden" id="import_title" value="'.Yii::t('user', 'Import Feedback Request List').'" />';
} else {
	$this->title = Yii::t('user', ($activeTab == 'addStaff') ? 'Add Customer' : 'Import Feedback Request List');
	echo '<input type="hidden" id="add_staff_title" value="'.Yii::t('user', 'Add Customer').'" />';
	echo '<input type="hidden" id="import_title" value="'.Yii::t('user', 'Import Feedback Request List').'" />';
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dashboard client add_staff">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-sx-12">
			<ul class="nav nav-tabs">
				<li class="add_staff <?php echo ($activeTab == 'addStaff') ? 'active' : ''; ?>" >
					<a data-toggle="tab" href="#addStaff">
						<?php
							if($role == "client_admin"){
								echo "Add Staff Member";
								$set_role = "client_staff";
							} else {
								echo "Add Customer";
								$set_role = "end_user";
							}
						?>
					</a>
				</li>
				<!-- <li><a data-toggle="tab" href="#importStaff">Import Staff Members</a></li> -->
				<li class="import <?php echo ($activeTab == 'importUser') ? 'active' : ''; ?>" ><a data-toggle="tab" href="#importUser">Import Customers</a></li>
			</ul>

			<div class="tab-content">

				<div id="addStaff" class="tab-pane fade <?php echo ($activeTab == 'addStaff') ? 'in active' : ''; ?>">
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
			
				<div id="importUser" class="tab-pane fade <?php echo ($activeTab == 'importUser') ? 'in active' : ''; ?>">
					<h3>Import Customers to request feedback</h3>
					<?php $form = ActiveForm::begin(['id' => 'import-users-form', 
						// 'action' => 'index.php?r=/user/clientDashboard/uploadcsv',
						'action' => ['/user/clientDashboard/uploadcsv'],
						'enableAjaxValidation' => false, 'enableClientValidation' => true,  'options' => ['enctype' => 'multipart/form-data'] ]); ?>
						<div class="col-md-6">
							<?= $form->field($importUserModel, 'location_id')->dropdownlist($locations) ?>
							<?= $form->field($importUserModel, 'csvfile')->fileInput() ?>
							<?= Html::submitButton(Yii::t('user', 'Import'), ['class' => 'btn btn-primary']) ?>
							<?php if($role == "client_admin"){ ?>
								<a href="<?= Url::to(['/user/clientDashboard/managestaff']) ?>" class="btn btn-default">Back</a>
							<?php } ?>
						</div>
						<div class="col-md-6">
							<h3>You can upload emails in an CSV file with this format:</h3>
							<table class="table table-bordered">
								<tr>
									<th><b>First</b></th>
									<th><b>Last</b></th>
									<th><b>Email(Required Field)</b></th>
									<th><b>Phone</b></th>
								</tr>
								<tr>
									<td>John</td>
									<td>Doe</td>
									<td>john@surveylocal.com</td>
									<td>800-556-8962</td>
								</tr>
								<tr>
									<td>Jane</td>
									<td>Doe</td>
									<td>jane@surveylocal.com</td>
									<td>800-556-8963</td>
								</tr>
							</table>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>