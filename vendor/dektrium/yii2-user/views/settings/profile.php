<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Business Information');
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->identity;
$role = $user->getRole();

?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu', [
            'business_name' => $model->getAttribute('business_name'),
        ]) ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <?php $form = \yii\widgets\ActiveForm::begin([
                    'id' => 'profile-form',
                    'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-lg-3 control-label'],
                    ],
                    //'enableAjaxValidation'   => true,
                    'enableClientValidation' => true,
                    'validateOnBlur'         => true,
                ]); ?>
                <?php if($role == "client_staff"){ ?>
                    <?= $form->field($model, 'name')->textinput(['readonly'=>true]) ?>
                <?php } else { ?> 
                    <?= $form->field($model, 'name') ?>
                <?php } ?>

                <?php // echo $form->field($model, 'public_email') ?>

                <?= $form->field($model, 'website') ?>

                <?= $form->field($model, 'business_name') ?>
                <?= $form->field($model, 'address')->textarea() ?>
                <?= $form->field($model, 'city') ?>
                <?= $form->field($model, 'state') ?>
                <?= $form->field($model, 'zip') ?>
                <?= $form->field($model, 'phone') ?>
                <?php // echo $form->field($model, 'google_page') ?>
                <?php // echo $form->field($model, 'facebook_page') ?>
                <?php // echo $form->field($model, 'trip_advisor') ?>
                <?php // echo $form->field($model, 'yelp') ?>
                <?php // echo $form->field($model, 'profile_url') ?> <?php //['placeholder' => 'e.g Your profile name is johns-pizza So your profile url will frontend.dev/johns-pizza'] ?>
                <?php // echo $form->field($model, 'place_id') ?>
                <?= $form->field($model, 'logo')->fileInput() ?>
                <?php if(!empty($model->getAttribute('logo'))) { ?>
                <div class="form-group field-profile-logo">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-9">
                    <img src="<?php echo Url::to('@web/images/logo/'.$model->getAttribute('user_id').'/'.$model->getAttribute('logo')); ?>" alt="Company Logo" class="company-logo-img" />        
                    </div>
                    <div class="col-sm-offset-3 col-lg-9"><div class="help-block"></div>
                    </div>
                </div>
                <?php } ?>
                <?php // echo $form->field($model, 'feedback_approach_text')->textarea() ?>
                <?php // echo $form->field($model, 'location') ?>

                <?php /* $form
                    ->field($model, 'timezone')
                    ->dropDownList(
                        \yii\helpers\ArrayHelper::map(
                            \dektrium\user\helpers\Timezone::getAll(),
                            'timezone',
                            'name'
                        )
                    );*/ ?>

                <?php // echo $form->field($model, 'gravatar_email')->hint(\yii\helpers\Html::a(Yii::t('user', 'Change your avatar at Gravatar.com'),'http://gravatar.com')) ?>

                <?php //echo $form->field($model, 'bio')->textarea() ?>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <?= \yii\helpers\Html::submitButton(
                            Yii::t('user', 'Save'),
                            ['class' => 'btn btn-block btn-success']
                        ) ?><br>
                    </div>
                </div>

                <?php \yii\widgets\ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
