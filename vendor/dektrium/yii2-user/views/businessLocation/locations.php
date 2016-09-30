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
use yii\helpers\ArrayHelper;
use dektrium\user\models\Profile;
use dektrium\user\models\BusinessLocation;
use yii\helpers\Url;

/**
 * @var yii\web\View                   $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module           $module
 */

$this->title = Yii::t('user', 'Business Locations');
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
			<div class="panel-heading"> Manage Business Locations </div>
			<div class="panel-body">
				<ul class="nav nav-tabs">
					<li class="active">
						<a data-toggle="tab" href="#locations">
							Business Locations
						</a>
					</li>
					<li><a data-toggle="tab" href="#location_map">Assign Location to Staff</a></li>
				</ul>
				<div class="tab-content">
					<div id="locations" class="tab-pane fade in active">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Location Name</th>
									<th>Address</th>
									<th>City</th>
									<th>State</th>
									<th>Zip</th>
									<th>Phone</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($locations as $key => $loc) { ?>
									<tr>
										<td><?= $loc->getAttribute('location_name') ?></td>
										<td><?= $loc->getAttribute('address') ?></td>
										<td><?= $loc->getAttribute('city') ?></td>
										<td><?= $loc->getAttribute('state') ?></td>
										<td><?= $loc->getAttribute('zip') ?></td>
										<td><?= $loc->getAttribute('phone') ?></td>
										<?php 
											$editurl = Url::toRoute(['/user/businessLocation/edit', 'id' => $loc->getAttribute('id')]);
											$deleteurl = Url::toRoute(['/user/businessLocation/delete', 'id' => $loc->getAttribute('id')]);
										?>
										<td><a href="<?= $editurl ?>"><span class="glyphicon glyphicon-edit"></span></a></td>
										<td><a href="<?= $deleteurl ?>" class="text-danger"><span class="glyphicon glyphicon-trash"></span></a></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<!-- <a href="index.php?r=user/businessLocation/new" class="btn btn-primary">Add New Location</a> -->
						<a href="<?= Url::to(['/user/businessLocation/new']) ?>" class="btn btn-primary">Add New Location</a>
					</div>
					<div id="location_map" class="tab-pane fade">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Staff Member</th>
									<th>Location Name</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php if(count($assignedLocations)) { ?>
									<?php foreach ($assignedLocations as $key => $loc) { ?>
										<tr>
											<td><?= $loc['staff_name'] ?></td>
											<td><?= $loc['location_name'] ?></td>
											<!--td><a href="index.php?r=user/businessLocation/deletemap&id=<?php  // $loc['assign_id'] ?>" class="text-danger"><span class="glyphicon glyphicon-trash"></span></a></td-->
											<td><a href="<?= Url::toRoute(['/user/businessLocation/deletemap', 'id' => $loc['assign_id']]) ?>" class="text-danger"><span class="glyphicon glyphicon-trash"></span></a></td>
										</tr>
									<?php } ?>
								<?php } else { ?>
									<tr><td colspan="3">No user assigned to any location yet.</td></tr>
								<?php } ?>
							</tbody>
						</table>
						<hr/>
						<?php $form = ActiveForm::begin(['id' => 'staff-location-map-form', 
							// 'action' => 'index.php?r=/user/businessLocation/staffmap', 
							'action' => ['/user/businessLocation/staffmap'], 
							'enableAjaxValidation' => false, 'enableClientValidation' => true,  'options' => ['enctype' => 'multipart/form-data'] ]); ?>
							<?= $form->field($modelMap, 'staff_id')->dropDownList($showUsers) ?>
							<?= $form->field($modelMap, 'location_id')->dropDownList($showLocations) ?>
							<?= Html::submitButton(Yii::t('user', 'Assign'), ['class' => 'btn btn-primary']) ?>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>