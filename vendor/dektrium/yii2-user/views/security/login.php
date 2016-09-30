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

$this->title = Yii::t('user', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

<!--?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?-->
<div class="site-login">
    <div class="row login-signup-container">
        <div class="col-lg-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id'                     => 'login-form',
                        'enableAjaxValidation'   => true,
                        'enableClientValidation' => false,
                        'validateOnBlur'         => false,
                        'validateOnType'         => false,
                        'validateOnChange'       => false,
                    ]) ?>

                    <?= $form->field(
                        $loginmodel,
                        'login',
                        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]
                    ) ?>

                    <?= $form
                        ->field(
                            $loginmodel,
                            'password',
                            ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']]
                        )
                        ->passwordInput()
                        ->label(
                            Yii::t('user', 'Password')
                            .($module->enablePasswordRecovery ?
                                ' (' . Html::a(
                                    Yii::t('user', 'Forgot password?'),
                                    ['/user/recovery/request'],
                                    ['tabindex' => '5']
                                )
                                . ')' : '')
                        ) ?>

                    <?= $form->field($loginmodel, 'rememberMe')->checkbox(['tabindex' => '4']) ?>

                    <?= Html::submitButton(
                        Yii::t('user', 'Sign in'),
                        ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']
                    ) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <?php if ($module->enableConfirmation): ?>
                <p class="text-center">
                    <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
                </p>
            <?php endif ?>
            <?php if ($module->enableRegistration): ?>
                <p class="text-center">
                    <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
                </p>
            <?php endif ?>
        </div>


        <div class="col-lg-2 verticleline ">
            <span class="or-circle">OR</span>
        </div>

        <div class="col-lg-5">

            <h1>Signup</h1>

            <p>Register to get an account.</p>

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($signupmodel, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($signupmodel, 'email') ?>

                <?= $form->field($signupmodel, 'password')->passwordInput() ?>

                <!-- CUSTOM NIKHIL... -->
                    <!-- from frontend, user registration, it should be client_admin always -->
                    <?= Html::activeHiddenInput($signupmodel, 'role', ['value' => 'client_admin']) ?>
                <!-- CUSTOM NIKHIL... -->
                
                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

            <h1>Register with Social Media</h1>

            <?= Connect::widget([
                'baseAuthUrl' => ['/user/security/auth'],
            ]) ?>
            
            <!-- <div class="form-group">
                <?= Html::button('Facebook', ['class' => 'btn btn-primary', 'name' => 'facebook-button']) ?>
            </div>
            <div class="form-group">
                <?= Html::button('Google', ['class' => 'btn btn-primary', 'name' => 'google-button']) ?>
            </div>
            <div class="form-group">
                <?= Html::button('Twitter', ['class' => 'btn btn-primary', 'name' => 'twitter-button']) ?>
            </div> -->

        </div>
    </div>
</div>
