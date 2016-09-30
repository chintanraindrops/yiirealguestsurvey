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

$this->title = Yii::t('user', 'Import Feedback Request List');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dashboard client add_staff">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-sx-12">
			<div id="importUser">
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

							<?php
							if(!empty(Yii::$app->request->get('return'))){
								?>
								<a href="<?= Url::to(['/user/clientDashboard/requestfeedback']) ?>" class="btn btn-default">Back</a><?php
							} else {
								?>
								<a href="<?= Url::to(['/user/clientDashboard/managestaff']) ?>" class="btn btn-default">Back</a><?php
							}
						} ?>
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