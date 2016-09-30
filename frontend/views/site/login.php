<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="row login-signup-container">
        <div class="col-lg-5">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>Already have an account? Please Login.</p>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($loginmodel, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($loginmodel, 'password')->passwordInput() ?>

                <?= $form->field($loginmodel, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    <?= Html::a('Forgot Passoword?', ['site/request-password-reset']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
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

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

            <h1>Register with Social Media</h1>
            
            <div class="form-group">
                <?= Html::button('Facebook', ['class' => 'btn btn-primary', 'name' => 'facebook-button']) ?>
            </div>
            <div class="form-group">
                <?= Html::button('Google', ['class' => 'btn btn-primary', 'name' => 'google-button']) ?>
            </div>
            <div class="form-group">
                <?= Html::button('Twitter', ['class' => 'btn btn-primary', 'name' => 'twitter-button']) ?>
            </div>

        </div>
    </div>
</div>
