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

$this->title = Yii::t('user', 'My Feedback');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="feedback-detail row">
	<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php 
				if(empty($feedback->getAttribute('from_email'))) {
					if(empty($feedback->getAttribute('from_mobile'))) {
						echo "My Feedback";
					} else {
						echo $feedback->getAttribute('from_mobile');
					}
				} else {
					echo $feedback->getAttribute('from_email');
				}
			?>
		</div>
		<div class="panel-body">
			<table class="table">
				<tr>
					<th>Client (who gets feedback)</th>
					<td>
						<?php
							$userObj = $finder->findUserById($feedback->getAttribute('user_id'));
							$profileObj = $finder->findProfileById($feedback->getAttribute('user_id'));

							$client_business_user_name = (!empty($profileObj->business_name)) ? $profileObj->business_name : $userObj->username ;
							echo $client_business_user_name;
						?>
					</td>
				</tr>
				<tr>
					<th>Type</th>
					<th>
						<?php
							if(strtoupper($feedback->getAttribute('type')) == "POSITIVE"){
								echo '<span class="text-success">'.strtoupper($feedback->getAttribute('type')).'</span>';
							} else {
								echo '<span class="text-danger">'.strtoupper($feedback->getAttribute('type')).'</span>';
							}
						?>
					</th>
				</tr>
				<tr>
					<th>Created At</th>
					<td><?= date('d-m-Y H:i', $feedback->getAttribute('created_at')) ?></td>
				</tr>
				<tr>
					<th>First Name</th>
					<td><?= $feedback->getAttribute('from_firstname') ?></td>
				</tr>
				<tr>
					<th>Last Name</th>
					<td><?= $feedback->getAttribute('from_lastname') ?></td>
				</tr>
				<tr>
					<th>Email</th>
					<td><?= $feedback->getAttribute('from_email') ?></td>
				</tr>
				<tr>
					<th>Mobile</th>
					<td><?= $feedback->getAttribute('from_mobile') ?></td>
				</tr>
				<tr>
					<th>Notes</th>
					<td><?= $feedback->getAttribute('notes') ?></td>
				</tr>
				<tr>
					<td colspan="2">
						<?php 
							$name = "";
							if(!empty($feedback->getAttribute('from_firstname'))){
								$name = $feedback->getAttribute('from_firstname');
							}
							if(!empty($feedback->getAttribute('from_lastname'))){
								if(empty($name))
									$name = $feedback->getAttribute('from_lastname');
								else {
									$name .= " ".$feedback->getAttribute('from_lastname');
								}
							}
							if(empty($name))
								$name = "Customer";

							echo $name." has requested you to contact him/her - <b>".(($feedback->getAttribute('contact_me')) ? "Yes" : "No")."</b>";
						?>
					</td>
				</tr>
			</table>
			<a href="index.php?r=user/admin/feedbacks" class="btn btn-default">Back</a>
		</div>
	</div>
</div>