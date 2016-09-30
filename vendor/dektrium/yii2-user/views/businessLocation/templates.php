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

$this->title = Yii::t('user', 'Edit Email Templates');
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
			<div class="panel-heading"><?= Yii::t('user', 'Email Templates') ?></div>
			<div class="panel-body">
				<div id="templates">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<!-- <th>ID</th> -->
								<th>Title</th>
								<th>Template</th>
								<!--<th>Is Active?</th>-->
								<th>Edit</th>
								<!--<th>Delete</th>-->
							</tr>
						</thead>
						<tbody>
							<?php foreach ($templates as $key => $template) { ?>
								<tr>
									<!--td --><?php // $template->getAttribute('id') ?><!--/td-->
									<td><?= $template->getAttribute('title') ?></td>
									<td><?= nl2br($template->getAttribute('template')) ?></td>
									<!--<td>--><?php /* ($template->getAttribute('active')) ? Yii::t('user', 'Yes') : Yii::t('user', 'No') */ ?><!--</td>-->
									<?php 
										$editurl = Url::toRoute(['/user/businessLocation/edittemplate', 'id' => $template->getAttribute('id')]);
										$deleteurl = Url::toRoute(['/user/businessLocation/deletetemplate', 'id' => $template->getAttribute('id')]);
									?>
									<td><a href="<?= $editurl ?>"><span class="glyphicon glyphicon-edit"></span></a></td>
									<!--<td><a href="" class="text-danger"><span class="glyphicon glyphicon-trash"></span></a></td>--> <?php // a href $deleteurl ?>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php if($allow_add) { ?>
						<a href="<?= Url::to(['/user/businessLocation/newtemplate']) ?>" class="btn btn-primary">Add New Template</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>