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

$this->title = Yii::t('user', 'Client Dashboard');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dashboard client">
	<div class="row">
		<h2>index.php</h2>
		<div class="col-xs-12 col-sm-6 col-md-6">
			<h1>Import End Users</h1>
			<h1>Import Staff</h1>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6">
			<h1>Add Staff</h1>
		</div>
	</div>
</div>