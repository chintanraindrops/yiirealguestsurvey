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
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Yii::t('user', 'Hello') ?>,
</p>

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Yii::t('user', 'A client need your help to setup account') ?>.
</p>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Yii::t('user', 'Below are the details of client') ?>:
</p>
<p>    
    <?= Yii::t('user', 'Username') ?>: <strong><?= $username ?></strong><br/>
    <?= Yii::t('user', 'Password') ?>: <strong><?= $password ?></strong><br/>
    <?= Yii::t('user', 'Name') ?>: <strong><?= $profile->name ?></strong><br/>
    <?= Yii::t('user', 'Business Name') ?>: <strong><?= $profile->business_name ?></strong><br/>
    <?= Yii::t('user', 'Address') ?>: <strong><?= $profile->address ?></strong><br/>
    <?= Yii::t('user', 'City') ?>: <strong><?= $profile->city ?></strong><br/>
    <?= Yii::t('user', 'State') ?>: <strong><?= $profile->state ?></strong><br/>
    <?= Yii::t('user', 'Zip') ?>: <strong><?= $profile->zip ?></strong><br/>
    <?= Yii::t('user', 'Contact Phone') ?>: <strong><?= $profile->phone ?></strong><br/>
    <?= Yii::t('user', 'Website') ?>: <strong><?= $profile->website ?></strong><br/>
    <?= Yii::t('user', 'Goolgle My Business Page') ?>: <strong><?= $profile->google_page ?></strong><br/>
    <?= Yii::t('user', 'Facebook Page') ?>: <strong><?= $profile->facebook_page ?></strong><br/>
    <?= Yii::t('user', 'Trip Advisor') ?>: <strong><?= $profile->trip_advisor ?></strong><br/>
    <?= Yii::t('user', 'Yelp') ?>: <strong><?= $profile->yelp ?></strong><br/>
</p>

<p> <?= Yii::t('user', 'Locations') ?>: </p>
<p>
<?php foreach ($locations as $key => $value) { ?>
	<strong><?= Yii::t('user', 'Location') ?> - <?= ($key+1) ?></strong><br/>
	<?= Yii::t('user', 'Business Name') ?>: <strong><?= $value['business_name'] ?></strong><br/>
	<?= Yii::t('user', 'Address') ?>: <strong><?= $value['address'] ?>	</strong><br/>
	<?= Yii::t('user', 'City') ?>: <strong><?= $value['city'] ?>	</strong><br/>
	<?= Yii::t('user', 'State') ?>: <strong><?= $value['state'] ?>	</strong><br/>
	<?= Yii::t('user', 'Zip') ?>: <strong><?= $value['zip'] ?>	</strong><br/>
	<?= Yii::t('user', 'Phone') ?>: <strong><?= $value['phone'] ?></strong><br/><br/>
<?php } ?>
</p>