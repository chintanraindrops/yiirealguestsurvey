<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    $endUserPages = isset(Yii::$app->params['endUserPages']) ? Yii::$app->params['endUserPages'] : '';
    
    if (in_array($this->context->action->id, $endUserPages)) {
        NavBar::begin([
            'brandLabel' => 'Real Guest User Survey',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
                'style' => 'background-color:navy',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right']
        ]);
        NavBar::end();
    } else {
        NavBar::begin([
            'brandLabel' => 'Real Guest User Survey',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
                'style' => 'background-color:navy',
            ],
        ]);
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
        ];
        if (Yii::$app->user->isGuest) {
            //$menuItems[] = ['label' => 'Signup', 'url' => ['/user/registration/register']];
            $menuItems[] = ['label' => 'Login/Signup', 'url' => ['/user/security/login']];
        } else {
            // CUSTOM NIKHIL...
            $user = Yii::$app->user;
            $role = $user->identity->getRole();
            $client_admin = '<li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">My Account
                                <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                  <li><a href="'.Url::to(['/user/clientDashboard/managestaff']).'">Manage Staff</a></li>
                                  <li><a href="'.Url::to(['/user/clientDashboard/requestfeedback']).'">Request Feedback</a></li>
                                  <li><a href="'.Url::to(['/user/profile']).'">Profile</a></li>
                                  <li><a href="'.Url::to(['/user/clientDashboard/feedbacks']).'">My Feedbacks</a></li>
                                </ul>
                            </li>';
                                  // <li><a href="'.Url::to(['/user/businessLocation/locations']).'">Manage Business Locations</a></li>
            $client_staff = '<li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">My Account
                                <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                  <li><a href="'.Url::to(['/user/clientDashboard/dashboard']).'">Add Customer</a></li>
                                  <li><a href="'.Url::to(['/user/clientDashboard/importcustomer']).'">Import Customers</a></li>
                                  <li><a href="'.Url::to(['/user/clientDashboard/requestfeedback']).'"">Request Feedback</a></li>
                                  <li><a href="'.Url::to(['/user/profile']).'">Profile</a></li>
                                  <li><a href="'.Url::to(['/user/clientDashboard/feedbacks']).'">My Feedbacks</a></li>
                                </ul>
                            </li>';
            $end_user = '<li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">My Account
                                    <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                      <li><a href="#">Give Feedback</a></li>
                                      <li><a href="'.Url::to(['/user/profile">Profile']).'"</a></li>
                                    </ul>
                                </li>';
            $super_admin = '';
            $menuItems[] = $$role;
            // CUSTOM NIKHIL...

            //$menuItems[] = ['label' => 'Profile', 'url' => ['/user/profile']];
            $menuItems[] = [
                'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                'url' => ['/user/security/logout'],
                'linkOptions' => ['data-method' => 'post']
            ];
        }
        //echo '<pre>';print_r($menuItems);exit;
        /*if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            $menuItems[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>';
        }*/
        /*echo Nav::widget(
        array(
        "options" => array("class" => "navbar-nav navbar-right"),
        "items"=>array(
          array(
            "url"=>array(),
            "label"=>"Products",
            array(
                "url"=>array("/site/index"),
                "label"=>"Create product"
            ),
          ),
        )
        )
        );*/
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
    ?>

    <div class="container">
        <?php if (!in_array($this->context->action->id, $endUserPages)) { ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php } ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Real Guest User Survey <?= date('Y') ?></p>

        <p class="pull-right"><?php /* Yii::powered() */ ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
<?php $this->registerJsFile('/js/custom_ri.js'); ?>

</body>
</html>
<?php $this->endPage() ?>
