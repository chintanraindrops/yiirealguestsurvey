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
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use dektrium\user\models\BusinessLocation;

/**
 * @var yii\web\View                   $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module           $module
 */

$this->title = Yii::t('user', 'Request Feedback');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requestfeedback client row">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id'                     => 'registration-form',
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                ]); ?>

                <?php
                $locations = array();
                foreach ($locationsObj as $location) {
                    //var_dump($location->getAttribute('location_name'));
                    $locations[$location->getAttribute('id')] = $location->getAttribute('location_name');
                }
                //var_dump($locations);exit;
                ?>

                <?= $form->field($model, 'location_id')->dropdownlist($locations) ?>

                <?= $form->field($model, 'firstname') ?>

                <?= $form->field($model, 'lastname') ?>

                <?= $form->field($model, 'email') ?>

                <div class="or_seperator" style="text-align:center;">Or</div>

                <?= $form->field($model, 'mobile') ?>

                <?= Html::submitButton(Yii::t('user', 'Send Request'), ['class' => 'btn btn-success btn-block']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="or_seperator" style="text-align:center;">Or to send multiple requests</div>
        <p class="text-center"><a class="upload_list_link" href="<?= Url::toRoute(['/user/clientDashboard/importcustomer', 'return' => 'requestfeedback'], true); ?>">Click here to upload customers list.</a></p>
    </div>
</div>