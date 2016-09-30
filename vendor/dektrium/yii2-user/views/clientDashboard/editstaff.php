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
use dektrium\user\models\User;

/**
 * @var yii\web\View                   $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module           $module
 */

$this->title = Yii::t('user', 'Manage Staff');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="businessLocation row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"> Edit Staff </div>
            <div class="panel-body">
                <h3>Edit Staff Member</h3>
                <?php $form = ActiveForm::begin(['id' => 'form-signup', 
                    'action' => ['/user/clientDashboard/updatestaff'],
                ]); ?>
                    <?= $form->field($modelProfile, 'name')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($registrationModel, 'username') ?>
                    <?= $form->field($registrationModel, 'email') ?>
                    <?= $form->field($registrationModel, 'password')->passwordInput() ?>
                    <?= Html::activeHiddenInput($registrationModel, 'id') ?>
                    <?= Html::activeHiddenInput($modelProfile, 'user_id') ?>
                    <div class="form-group">
                        <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                        <a href="<?= Url::to(['/user/clientDashboard/managestaff']) ?>" class="btn btn-default">Cancel</a>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>