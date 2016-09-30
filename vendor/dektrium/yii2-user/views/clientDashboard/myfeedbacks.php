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

$this->title = Yii::t('user', 'My Feedbacks');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="feedback-listing row">
	<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading"> My Feedbacks </div>
		<div class="panel-body">
			<table class="table">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Type</th>
						<th>From</th>
						<th>Created At</th>
					</tr>
				</thead>
				<tbody>
					<?php if(count($feedbacks)){ ?>
						<?php foreach ($feedbacks as $feedback) { ?>
							<tr>
								<td>
									<a href="<?php echo Url::toRoute(['/user/clientDashboard/feedback', 'id' => $feedback->getAttribute('id')]); ?>">
										<span class="glyphicon glyphicon-eye-open"></span>
									</a>
								</td>
								<th>
									<?php
										if(strtoupper($feedback->getAttribute('type')) == "POSITIVE"){
											echo '<span class="text-success">'.strtoupper($feedback->getAttribute('type')).'</span>';
										} else {
											echo '<span class="text-danger">'.strtoupper($feedback->getAttribute('type')).'</span>';
										}
									?>
								</th>
								<td>
									<?php 
										if(empty($feedback->getAttribute('from_email'))) {
											echo $feedback->getAttribute('from_mobile');
										} else {
											echo $feedback->getAttribute('from_email');
										}
									?>
								</td>
								<td><?= date('d-m-Y H:i', $feedback->getAttribute('created_at')) ?></td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td colspan="4">No feedback found.</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>