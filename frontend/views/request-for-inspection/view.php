<?php

use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model app\models\RequestForInspection */

$this->title = $model->rfi_number;
$this->params['breadcrumbs'][] = ['label' => 'Request For Inspections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$requested_by = '';
$chairperson = !empty($model->fk_chairperson) ? $model->chairperson->getEmployeeDetails() : [];
$inspector = !empty($model->fk_inspector) ? $model->inspector->getEmployeeDetails() : [];
$property_unit = !empty($model->fk_property_unit) ? $model->propertyUnit->getEmployeeDetails() : [];
$requested_by = !empty($model->fk_requested_by) ? $model->requestedBy->getEmployeeDetails() : [];
$officeName = $model->office->office_name;
$isProvincialOffice = strtolower($officeName)  === 'ro' ? false : true;
?>
<div class="request-for-inspection-view" id='app'>
    <div class="container card">
        <h5 class='note'>
            *Note:
            <br>
            &emsp;• Click Final button to generate IR.
            <br>
            &emsp;• RFI that is already finalized cannot be edited.
        </h5>
        <p>
            <?php
            // if (!$model->is_final && Yii::$app->user->can('update_request_for_inspection')) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) . ' ';
            // }
            if (Yii::$app->user->can('final_request_for_inspection')) {
                echo Html::a('Final', ['final', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to final this item?',
                        'method' => 'post',
                    ],
                ]);
            }
            ?>
        </p>
        <template>
            <table>
                <tr>
                    <th colspan="7" class="center">
                        FOR INSPECTION AND ACCEPTANCE
                        <hr>

                    </th>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="2">

                        <span> Date:</span>
                        <?php
                        if (!empty($model->date)) {
                            echo   DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y');
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="2">
                        <span>No.:</span>
                        <?= $model->rfi_number ?>
                    </td>
                </tr>
                <tr>
                    <th colspan="7" class="center">REQUEST FOR INSPECTION
                        <br>
                        <br>

                    </th>
                </tr>
                <tr>
                    <td colspan="7">

                        <span style="font-weight: bold;"><?= !empty($chairperson['fullName']) ? strtoupper($chairperson['fullName']) : '' ?></span>
                        <br>
                        <?php

                        if ($isProvincialOffice === false) :
                        ?>
                            <span>Chairperson</span>
                            <br>
                            <span>Inspection Commitee</span>
                            <br>
                        <?php else : ?>
                            <span>Inspection Officer</span>
                            <br>
                        <?php endif; ?>
                        <span>DTI-Caraga, <?= $officeName ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Madam:</span>
                        <br>
                        <br>

                    </td>
                    <td colspan="6"></td>
                </tr>
                <tr>
                    <td colspan="7">
                        <span>This is to request inspection for the following:</span>
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <?= $model->transaction_type === 'with_po' ? '<th class="center">PO No.</th>' : '' ?>
                    <th class="center">Name of Activity</th>
                    <th class="center">Payee</th>
                    <th>Description</th>
                    <th class="center">Quantity</th>
                    <th class="center">Unit Cost</th>
                    <th class="center">From Date</th>
                    <th class="center">To Date</th>

                </tr>
                <?php if ($model->transaction_type === 'with_po') : ?>
                    <tr v-for="(item, index) in withPoItems" :key="index">
                        <td>{{ item.po_number }}</td>
                        <td>{{ item.purpose }}</td>
                        <td>{{ item.payee }}</td>
                        <td><b>{{ item.stock_title }}</b> <br> {{ item.specification}}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ formatUnitCost(item.unit_cost) }}</td>
                        <td>{{ formatDate(item.date_from) }}</td>
                        <td>{{ formatDate(item.date_to) }}</td>

                        <?php if (Yii::$app->user->can('ro_procurement_admin') || Yii::$app->user->can('po_procurement_admin')) : ?>
                            <td>
                                <a :href="'/q/index.php?r=pr-purchase-order/view&id=' + item.po_id" class='btn btn-link'>PO Link</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php else : ?>
                    <tr v-for="(item,index) in withoutPoItems">
                        <td>{{ item.project_name  }} </td>
                        <td>{{ item.payee_name  }} </td>
                        <td><b>{{ item.stock_title  }}</b> <br>{{formatSpecs(item.specification)}} </td>
                        <td>{{ item.quantity  }} </td>
                        <td>{{ formatUnitCost(item.unit_cost)  }} </td>
                        <td>{{ formatDate(item.from_date) }}</td>
                        <td>{{ formatDate(item.to_date) }}</td>
                    </tr>
                <?php endif; ?>
                <?php
                // if ($model->transaction_type === 'with_po') {
                //     foreach ($model->getWithPoItems() as $val) {
                //         $from_date = !empty($val['date_from']) ? DateTime::createFromFormat('Y-m-d', $val['date_from'])->format('F d, Y') : '';
                //         $to_date = !empty($val['date_to']) ? DateTime::createFromFormat('Y-m-d', $val['date_to'])->format('F d, Y') : '';
                //         echo "<tr>
                //                 <td class='center v-align-top' >{$val['po_number']}</td>
                //                 <td class='center v-align-top'>{$val['purpose']}</td>
                //                 <td class='center v-align-top'>{$val['payee']}</td>
                //                 <td >
                //                 <span class='bold'>{$val['stock_title']}</span>
                //                 <br>
                //                 {$val['specification']}
                //                 </td>
                //                 <td class='center v-align-top'>{$val['quantity']}</td>
                //                 <td class='center v-align-top'>" . number_format($val['unit_cost'], 2) . "</td>
                //                 <td class='center v-align-top'>{$from_date}</td>
                //                 <td class='center v-align-top'>{$to_date}</td>";

                //         if (Yii::$app->user->can('ro_procurement_admin') || Yii::$app->user->can('po_procurement_admin')) {
                //             echo "<td>" . HTML::a('PO Link', ['pr-purchase-order/view', 'id' => $val['po_id']], ['class' => 'btn btn-link']) . "</td>";
                //         }
                //         echo " </tr>";
                //     }
                // } else {
                //     foreach ($model->getNoPoItems() as $item) {

                //         $project_name = $item['project_name'];
                //         $specification_view = str_replace('[n]', '<br>', $item['specification']);
                //         $unit_of_measure = $item['unit_of_measure'];
                //         $payee_name = $item['payee_name'];
                //         $unit_cost = $item['unit_cost'];
                //         $quantity = $item['quantity'];
                //         $from_date = $item['from_date'];
                //         $to_date = $item['to_date'];
                //         $stock_title = $item['stock_title'];

                //         echo "<tr>

                //         <td class='center v-align-top'>{$project_name}</td>
                //         <td class='center v-align-top'>{$payee_name}</td>
                //         <td >
                //         <span class='bold'>{$stock_title}</span>
                //         <br>
                //         {$specification_view}
                //         </td>
                //         <td class='center v-align-top'>{$quantity}</td>
                //         <td class='center v-align-top'>" . number_format($unit_cost, 2) . "</td>
                //         <td class='center v-align-top'>{$from_date}</td>
                //         <td class='center v-align-top'>{$to_date}</td>";
                //     }
                // }

                ?>
                <tr>
                    <td colspan="7"><br><br>Requested By</td>
                </tr>
                <tr>
                    <td colspan="3" class="center">
                        <br>
                        <br>
                        <br>
                        <span style="font-weight: bold;text-decoration:underline;"><?= !empty($requested_by) ?  strtoupper($requested_by['fullName']) : '' ?></span>
                        <br>
                        <span>Office/Division/Section/Unit Head</span>
                    </td>
                    <?php if ($isProvincialOffice === false) : ?>
                        <td colspan="4" class="center">
                            <br>
                            <br>
                            <br>
                            <span style="font-weight: bold;text-decoration:underline;"><?= !empty($inspector) ?  strtoupper($inspector['fullName']) : '' ?></span>
                            <br>
                            <span>Name /Signature of Inspector/Date</span>
                        </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                <?php endif; ?>
                <td colspan="4" class="center">
                    <br>
                    <br>
                    <br>
                    <span style="font-weight: bold;text-decoration:underline;"><?= !empty($property_unit) ?  strtoupper($property_unit['fullName']) : '' ?></span>
                    <br>
                    <span>Name /Signature of Supply/Property Unit Head / Date</span>
                </td>
                </tr>
            </table>

        </template>



    </div>
    <div class="container card">
        <table class="link table table-striped" style="margin-top: 5rem;">

            <thead>
                <tr>
                    <th colspan="2" class="center">
                        <h5>INSPECTION REQUEST LINKS</h5>
                    </th>
                </tr>
                <th style='text-align:center'>RFI No.</th>
                <th>Link</th>
            </thead>
            <tbody>

                <?php
                foreach ($model->getInspectionReportLinks() as $val) {
                    echo "<tr>
                <td style='text-align:center'>{$val['ir_number']}</td>
               <td > " . HTML::a('Link', ['inspection-report/view', 'id' => $val['id']], ['class' => 'btn btn-link']) . "</td>
            </tr>";
                }

                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/css/customCss.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<style>
    .container {
        background-color: white;
    }

    .card {
        padding: 1rem;
    }

    .note {
        color: red;
        font-style: italic;
    }

    th,
    td {
        padding: 5px;
    }

    table {
        width: 100%;
    }

    .center {
        text-align: center;
        max-width: 20rem;
    }

    .bold {
        font-weight: bold;
    }

    @media print {

        .btn,
        .main-footer,
        .link,
        .note {
            display: none;
        }


        th,
        td {
            padding: 2px;
            /* font-size: small; */
        }
    }
</style>
<script>
    new Vue({
        el: '#app',
        data: {
            withPoItems: <?= Json::encode($model->getWithPoItems()); ?>,
            withoutPoItems: <?= Json::encode($model->getNoPoItems()); ?>,
        },
        mounted() {
            // axios.get('https://jsonplaceholder.typicode.com/posts')
            //     .then(function(response) {
            //         // handle success
            //         console.log(response);
            //     })

        },
        computed: {
            totalAmount() {
                return this.withoutPoItems.reduce((total, item) => total + item.unit_cost * item.quantity, 0);
            }
        },
        methods: {
            formatUnitCost(unitCost) {
                unitCost = parseFloat(unitCost)
                if (typeof unitCost === 'number' && !isNaN(unitCost)) {
                    return unitCost.toLocaleString(); // Formats with commas based on user's locale
                }
                return 0; // If unitCost is not a number, return it as is
            },
            formatDate(date) {
                const dte = new Date(date)
                const options = {
                    timeZone: 'Asia/Manila',
                    year: 'numeric',
                    month: 'long',
                    day: '2-digit',
                };
                // return new Intl.DateTimeFormat('en-PH', options).format(dte);
                return dte.toLocaleString('en-PH', options);

            },
            formatSpecs(specs) {
                return specs.replace(/\[n\]/g, '\n');
            }

        }


    });
</script>