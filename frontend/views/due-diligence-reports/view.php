<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DueDiligenceReports */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Due Diligence Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$checkedLine = '<i class="fa fa-check"></i>';
$emptyCheckLine =  ' &nbsp; &nbsp;';
$notedBy = $model->notedBy->getEmployeeDetails();
$conductedBy =  $model->conductedBy->getEmployeeDetails();
?>
<div class="due-diligence-reports-view">
    <div class="container">

        <div class="card p-2">
            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </span>
        </div>
        <div class=" card p-2">
            <table>
                <tr>
                    <th colspan="2" class="text-center">
                        <span>Annex G</span><br>
                        <span>Due Diligence Report</span>
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <u><b>Due Diligence on Procurement</b></u><br>
                        <span>(To be conducted by RAPID Project PO/RCU/PCU)</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="w-10">Name of Supplier </span>
                        <span style="margin-left:1.2em">:</span>
                        <span class="underline w-75"><?= $model->supplier_name ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span>Address</span>
                        <span style="margin-left:5em">:</span>
                        <span class="underline w-75"><?= $model->supplier_address ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Contact Person
                        <span style="margin-left:2em">:</span>
                        <span class="underline w-75"><?= $model->supplier_contact_number ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Contact Number
                        <span style="margin-left:1.5em">:</span>
                        <span class="underline w-75"><?= $model->supplier_contact_person ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span>1. Legal Existence</span>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">1.1 Does the supplier have statutory Documents
                        <ul>
                            <li>SEC/CDA/DTI Registration</li>
                            <li>
                                <span>Yes</span>
                                <span class="underline">&nbsp;<?= $model->supplier_is_registered ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                                <span>No</span>
                                <span class="underline">&nbsp;<?= !$model->supplier_is_registered ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                            </li>
                            <li>Business Permit/ Mayor's Permit</li>
                            <li>
                                <span>Yes</span>
                                <span class="underline">&nbsp;<?= $model->supplier_has_business_permit ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                                <span>No</span>
                                <span class="underline">&nbsp;<?= !$model->supplier_has_business_permit ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                            </li>
                            <li>BIR Registration</li>
                            <li>
                                <span>Yes</span>
                                <span class="underline">&nbsp;<?= $model->supplier_is_bir_registered ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                                <span>No</span>
                                <span class="underline">&nbsp;<?= !$model->supplier_is_bir_registered ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span>2. Is the supplier related to any of the Officers of the Organization</span>
                        <br>
                        <ul>

                            <li>
                                <span>Yes</span>
                                <span class="underline">&nbsp;<?= $model->supplier_has_officer_connection ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                                <span>No</span>
                                <span class="underline">&nbsp;<?= !$model->supplier_has_officer_connection ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                            </li>
                        </ul>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">3. Is the supplier financially capable to deliver the goods/services
                        <ul>
                            <li>
                                <span>Yes</span>
                                <span class="underline">&nbsp;<?= $model->supplier_is_financial_capable ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                                <span>No</span>
                                <span class="underline">&nbsp;<?= !$model->supplier_is_financial_capable ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">4. Is the supplier an authorized manufacture/dealer/distributor of the product?
                        <ul>
                            <li>
                                <span>Yes</span>
                                <span class="underline">&nbsp;<?= $model->supplier_is_authorized_dealer ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                                <span>No</span>
                                <span class="underline">&nbsp;<?= !$model->supplier_is_authorized_dealer ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                            </li>
                            <li>

                                <ul>
                                    <li>4.1 Check physical existence of the store/warehouse/production area</li>
                                </ul>
                            </li>

                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">5. Does the supplier produce quality planting materials
                        <ul>
                            <li>
                                <span>Yes</span>
                                <span class="underline">&nbsp;<?= $model->supplier_has_quality_material ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                                <span>No</span>
                                <span class="underline">&nbsp;<?= !$model->supplier_has_quality_material ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                            </li>
                            <li>
                                <ul>
                                    <li>5.1 Check nursery (area of nursery, quality of seedling)</li>
                                </ul>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">6. Can the supplier meet the requirements of the organization
                        <ul>

                            <li>
                                <span>Yes</span>
                                <span class="underline">&nbsp;<?= $model->supplier_can_comply_specs ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                                <span>No</span>
                                <span class="underline">&nbsp;<?= !$model->supplier_can_comply_specs ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                            </li>
                        </ul>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">7. Has the supplier been involved in legal issues
                        <ul>

                            <li>
                                <span>Yes</span>
                                <span class="underline">&nbsp;<?= $model->supplier_has_legal_issues ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                                <span>No</span>
                                <span class="underline">&nbsp;<?= !$model->supplier_has_legal_issues ? $checkedLine : $emptyCheckLine ?>&nbsp;</span>
                            </li>
                        </ul>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">8. Clients/Customers
                        <ul>

                            <?php
                            foreach ($model->getItemsA() as $i => $item) {
                                $index = $i + 1;
                                echo " <li>8.{$index} <span class='underline w-75'> {$item['customer_name']}</span></li>";
                            }
                            ?>
                        </ul>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>Comments/Recommendation</p>
                        <u> <?= $model->comments ?></u>
                        <!-- <div class="underline w-100" ></div>
                    <div class="underline w-100" ></div> -->
                    </td>
                </tr>
                <tr>
                    <td><br>Conducted by:</td>
                    <td><br>Noted by:</td>
                </tr>
                <tr>
                    <td class="text-center">
                        <br>
                        <u> <b><?= !empty($conductedBy['fullName']) ? $conductedBy['fullName'] : '' ?></b></u><br>
                        <span><?= !empty($conductedBy['position']) ? $conductedBy['position'] : '' ?></span><br>
                        <span>DTI PCU <div class="underline" style="width: 5em;"></div></span><br>
                    </td>
                    <td class="text-center">
                        <br>
                        <u> <b><?= !empty($notedBy['fullName']) ? $notedBy['fullName'] : '' ?></b></u><br>
                        <span><?= !empty($notedBy['position']) ? $notedBy['position'] : '' ?></span><br>
                        <span>DTI Province <div class="underline" style="width: 5em;"></div></span><br>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<style>
    th,
    td {
        padding: 8px;
        /* border: 1px solid black; */
    }

    table {
        width: 100%;
    }

    .underline {
        border-bottom: 1px solid black;
        display: inline-block;
    }

    ul {
        list-style-type: none;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }

        th,
        td {
            padding: 5px;
            /* border: 1px solid black; */
        }
    }
</style>