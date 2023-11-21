<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NotificationToPay */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Notification To Pays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// var_dump($model->dueDiligenceReport->mgrfr->bankBranchDetail->address);
// var_dump($model->dueDiligenceReport->mgrfr->bankBranchDetail->bank_manager);
// var_dump($model->dueDiligenceReport->mgrfr->bankBranchDetail->bankBranch->branch_name);
// var_dump($model->dueDiligenceReport->mgrfr->bankBranchDetail->bankBranch->bank->name);
$provincialDirector = $model->provincialDirector->getEmployeeDetails();
$coordinator =  $model->coordinator->getEmployeeDetails();

?>
<div class="notification-to-pay-view">





    <div class="container">
        <div class="card p-2">
            <span>
                <?= Yii::$app->user->can('super-user') ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary lrgModal']) : '' ?>
            </span>
        </div>
        <div class="card p-2">
            <table>
                <thead>
                    <th class="text-center">
                        <h4 class="font-weight-bold">Annex H</h4>
                        <p>DTI Notification to Pay</p>
                    </th>
                </thead>
                <tbody>
                    <tr>
                        <td>DATE: <u> <?= DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y') ?></u></td>
                    </tr>
                    <tr>
                        <td>
                            <span>_____________________Name</span><br>
                            <span>
                                <u class="font-weight-bold">
                                    <?= strtoupper($model->dueDiligenceReport->mgrfr->bankBranchDetail->bankBranch->bank->name
                                        . '-' . $model->dueDiligenceReport->mgrfr->bankBranchDetail->bankBranch->branch_name) ?>
                                </u>
                                LBP/DBP
                            </span><br>
                            <span>_____________________Address of SFI</span><br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            RE: <u class="font-weight-bold text-uppercase"><?= $model->dueDiligenceReport->mgrfr->project_beneficiary ?></u>
                        </td>
                    </tr>
                    <tr>
                        <td>Dear __________________</td>
                    </tr>
                    <tr>
                        <td>
                            <p>
                                Relative to the memorandum of agreement signed by your bank under the RAPID Growth Project, you may proceed to debit the savings account number 
                                <u class="font-weight-bold text-uppercase"><?= $model->dueDiligenceReport->mgrfr->saving_account_number ?></u>
                                of the project beneficiary <?= $model->dueDiligenceReport->mgrfr->project_beneficiary ?></u> and to process the payment of the supplier. <br>
                                Attached is the authority to Debit signed by the project beneficiary authorizing your bank to debit from the account the combined amounts
                                of the matching grant and the equity counterpart and to pay the indicated supplier/s.
                                <br>
                                For more inquires and clarification, please do not hesitate to contact our RAPID Project Provincial Coordinator <u class="font-weight-bold"><?= !empty($coordinator['fullName']) ? $coordinator['fullName'] : '' ?>
                                </u>, mobile number <u class="font-weight-bold"><?= !empty($coordinator['mobile_number']) ? $coordinator['mobile_number'] : '________________________' ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Respectfully,</td>
                    </tr>

                    <tr>
                        <td>
                            <span><u class="font-weight-bold"><?= !empty($provincialDirector['fullName']) ? $provincialDirector['fullName'] : '' ?></u></span><br>
                            <span><?= !empty($provincialDirector['position']) ? $coordinator['position'] : '' ?></span><br>
                            Province ________
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    th,
    td {
        padding: 1em;
    }

    table {
        width: 100%;
    }

    @media print {

        .main-footer,
        .btn {
            display: none;
        }

        th,
        td {
            padding: 1em;
        }
    }
</style>