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
            <div class="panel-heading"> Manage Staff </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Confirmation</th>
                            <th>Block Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staffs as $key => $staff) { ?>
                            <tr>
                                <td><?= $staff->getAttribute('name') ?></td>
                                <td><?= User::find()->where(['id' => $staff->getAttribute("user_id")])->one()->getAttribute('username') ?></td>
                                <td><?= User::find()->where(['id' => $staff->getAttribute("user_id")])->one()->getAttribute('email') ?></td>
                                <?php 
                                    $confirmurl = Url::toRoute(['/user/clientDashboard/confirmstaff', 'id' => $staff->getAttribute('user_id')]);
                                    $blockurl = Url::toRoute(['/user/clientDashboard/blockstaff', 'id' => $staff->getAttribute('user_id')]);
                                    $unblockurl = Url::toRoute(['/user/clientDashboard/unblockstaff', 'id' => $staff->getAttribute('user_id')]);
                                    $editurl = Url::toRoute(['/user/clientDashboard/editstaff', 'id' => $staff->getAttribute('user_id')]);
                                    $deleteurl = Url::toRoute(['/user/clientDashboard/deletestaff', 'id' => $staff->getAttribute('user_id')]);
                                ?>
                                <td align="center">
                                    <?php if(User::find()->where(['id' => $staff->getAttribute("user_id")])->one()->getAttribute('confirmed_at')) { ?>
                                        <span class="text-success">Confirmed</span>
                                    <?php } else { ?>
                                        <a class="btn btn-xs btn-success btn-block" href="<?= $confirmurl ?>">Confirm</a>
                                    <?php } ?>
                                </td>
                                <td align="center">
                                    <?php if(User::find()->where(['id' => $staff->getAttribute("user_id")])->one()->getAttribute('blocked_at')) { ?>
                                        <a class="btn btn-xs btn-success btn-block" href="<?= $unblockurl ?>">Unblock</a>
                                    <?php } else { ?>
                                        <a class="btn btn-xs btn-danger btn-block" href="<?= $blockurl ?>">Block</a>
                                    <?php } ?>
                                </td>
                                <td align="center"><a href="<?= $editurl ?>"><span class="glyphicon glyphicon-edit"></span></a></td>
                                <td align="center"><a href="<?= $deleteurl ?>" class="text-danger"><span class="glyphicon glyphicon-trash"></span></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="<?= Url::to(['/user/clientDashboard/dashboard']) ?>" class="btn btn-primary">Add New Staff</a>
                <a href="<?= Url::to(['/user/clientDashboard/importcustomer']) ?>" class="btn btn-primary">Import Customers</a>
            </div>
        </div>
    </div>
</div>