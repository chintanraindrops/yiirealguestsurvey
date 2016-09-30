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

// $this->title = Yii::t('user', 'Add Location');
$this->title = $business_name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="emailtemplates edit row">
	<div class="col-md-3">
        <?= $this->render('_menu', [
            'business_name' => $business_name,
        ]) ?>
    </div>
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">Add Email Tempalte</div>
			<div class="panel-body">
				<?php $form = ActiveForm::begin([
						'id' => 'email-templates',
						'options' => ['class' => 'form profile'],
						'enableAjaxValidation'   => false,
						'enableClientValidation' => true,
						'validateOnBlur'         => true,
						'action' => ['/user/businessLocation/updatetemplate']
					]); ?>
			
					<?= Html::activeHiddenInput($templateForm, 'id', ['value' => $templateForm->getAttribute("id")]) ?>
					<?= Html::activeHiddenInput($templateForm, 'user_id', ['value' => \Yii::$app->user->id]) ?>
					<?= Html::activeHiddenInput($templateForm, 'template_id', ['value'=>$templateForm->getAttribute("template_id")]) ?>
					<?= $form->field($templateForm, 'title')->textinput(['readonly'=>'readonly', 'value'=>$templateForm->getAttribute("title")]) ?>
					<p class="hide text-primary" id="params">
						You can use any of the following parameters in your email template <br/>
						(customer), (manager), (feedback_link), (business), {customer}, {manager}, {business}, {feedback_link}
					</p>
					<?= $form->field($templateForm, 'template')->textarea(['rows'=>10, 'value'=>$templateForm->getAttribute("template")]) ?>
					<?php /* $form->field($templateForm, 'active')->checkbox(['value'=>$templateForm->getAttribute("active")]) */ ?>

					<div class="form-group">
						<?= Html::submitButton(Yii::t('user', 'Update Template'), ['class' => 'btn btn-primary']) ?>
						&nbsp;&nbsp;
						<!-- <a href="index.php?r=user/businessLocation/locations" class="btn btn-default">Cancel</a> -->
						<a href="<?= Url::to(['/user/businessLocation/templates']) ?>" class="btn btn-default">Cancel</a>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>