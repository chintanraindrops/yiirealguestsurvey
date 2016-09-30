<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

?>
<?= Yii::t('app', 'Hello {0}', $feedbackreceivedData->client) ?>,
<?= Yii::t('app', 'You have received {0} feedback from {1}', $feedbackreceivedData->feedback_type, $feedbackreceivedData->end_user) ?>.

<?php if($feedbackreceivedData->contact_me) { ?>
<?= Yii::t('app', '{0} has requested you to contact him/her.', $feedbackreceivedData->end_user) ?>
<?php } ?>

<?= Yii::t('app', 'Please see feedback notes : ') ?>
<?= $feedbackreceivedData->feedback_notes ?>
