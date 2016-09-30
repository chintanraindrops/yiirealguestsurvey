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
<?= Yii::t('app', 'Hello {0}', $thankyouforfeedbackData->end_user) ?>,

<?= Yii::t('app', 'Thank you for giving feedback to {0}', $thankyouforfeedbackData->client) ?>.

<?php if($thankyouforfeedbackData->contact_me) { ?>
<?= Yii::t('app', 'He/She will contact you soon !') ?>
<?php } ?>
