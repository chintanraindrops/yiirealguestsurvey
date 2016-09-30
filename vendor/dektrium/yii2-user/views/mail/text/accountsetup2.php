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

/**
 * @var dektrium\user\Module        $module
 * @var dektrium\user\models\User   $user
 * @var dektrium\user\models\Token  $token
 * @var bool                        $showPassword
 */

?>

<?= Yii::t('user', 'Hello') ?>,

<?= Yii::t('user', 'A client need your help to setup account') ?>.
<?= Yii::t('user', 'Below are the details of client') ?>:
<?= Yii::t('user', 'Username') ?>: <?= $username ?>
<?= Yii::t('user', 'Password') ?>: <?= $password ?>
<?= Yii::t('user', 'Name') ?>: <?= $profile->name ?>
<?= Yii::t('user', 'Business Name') ?>: <?= $profile->business_name ?>
<?= Yii::t('user', 'Address') ?>: <?= $profile->address ?>
<?= Yii::t('user', 'City') ?>: <?= $profile->city ?>
<?= Yii::t('user', 'State') ?>: <?= $profile->state ?>
<?= Yii::t('user', 'Zip') ?>: <?= $profile->zip ?>
<?= Yii::t('user', 'Contact Phone') ?>: <?= $profile->phone ?>
<?= Yii::t('user', 'Website') ?>: <?= $profile->website ?>