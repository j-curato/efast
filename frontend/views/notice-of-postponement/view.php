<?php

use app\models\BacPosition;
use app\models\Employee;
use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NoticeOfPostponement */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Notice Of Postponements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$approved_by = Employee::getEmployeeById($model->bacMember->employee_id ?? null);
$toDate = DateTime::createFromFormat('Y-m-d H:i:s', $model->to_date);
?>
<?= $this->render('/modules/download_pdf_with_header', [
    'date' => $toDate->format('F d, Y'),
    'serial_number' => $model->serial_number,
    'fileName' => 'Notice of Postponement',
    'headerTexts' => null,

]) ?>
<div class="notice-of-postponement-view" id="main">
    <div class="container card card-primary p-4">
        <ul>
            <li class="text-danger">The NOP cannot be updated if it is already marked as final.</li>
            <li class="text-danger">Finalize the NOP to reflect the dates in the AOQ for the selected RFQs.</li>
            <li class="text-danger">To print, click the 'Download PDF' button.</li>
        </ul>
        <p>
            <?php
            if (!$model->is_final) {
                echo Html::a('Final', ['final', 'id' => $model->id], ['class' => 'btn btn-success', '@click' => 'final']);
            } else {
                echo "<h5 style='color:red;'>This NOP is already Final.</h5>";
            }
            ?>
            <?php
            if (Yii::$app->user->can('update_notice_of_postponement')) {
                if (!$model->is_final) {
                    echo Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']);
                }
            }
            ?>
            <button onclick="generatePDF() " class="btn "> <i class="fa fa-file-pdf"></i> Download PDF</button>
        </p>

        <table class="pdf-export">

            <tr>
                <th colspan="3" class="text-center border-0">
                    <u style="font-size:larger;"> NOTICE OF POSTPONEMENT</u>
                </th>
            </tr>
            <tr>
                <td colspan="3" class="border-0">
                    <p class=""> <span class="ml-4"></span>Due to the <?= $model->type == 1 ? 'non-quorum of RBAC members' : 'short period of time prior to the opening' ?>
                        , Notice of Postponement is hereby issued for the following procurement activities pursuant to Section 29 of the 2016 Revised IRR of RA 9184, to wit:</p>
                </td>
            </tr>
            <tr>
                <th class="text-center">ACTIVITY</th>
                <th class="text-center"><u>From <br>(Date/Time)</u></th>
                <th class="text-center"><u>to <br>(Date/Time)</u></th>
            </tr>

            <tr v-for="item in items">
                <th class="">{{item.rfq_number}} - {{item.purpose}}</th>
                <td class="text-center">{{formatDate(item.from_date)}} 3:00 PM</td>
                <td class="text-center"><?= $toDate->format('F d, Y h:i A') ?> </td>
            </tr>
            <tr>
                <td colspan="3" class="border-left-0 border-right-0 border-bottom-0">
                    <br><br><br>
                    <p class="mt-5">
                        <span class="ml-4"></span> Deadline for the submission of quotation is on
                        <?= $toDate->format('F d, Y') . ' at ' . $toDate->format('h:i a') ?>
                        <b>Late bids/quotations will not be accepted.</b> The BAC shall take custody of bids submitted on or before the deadline of submission to ensure integrity, security and confidentiality.
                    </p>
                    <p class="ml-4">For information of all concerned.</p>
                </td>
            </tr>
            <tr>
                <td colspan="" class="border-0"> </td>
                <th colspan="2" class="text-center border-0 pt-5" style="vertical-align: bottom;">
                    <br>
                    <br>
                    <u style="font-weight:bold"><b><?= !empty($approved_by['employee_name']) ? strtoupper($approved_by['employee_name']) : '' ?></b></u><br>
                </th>
            </tr>
            <tr>
                <td colspan="" class="border-0"> </td>
                <td colspan="2" class="text-capitalize p-0 border-0 text-center" style="vertical-align: top;">
                    <?= !empty($model->bacMember->bacPosition->position) ? ucfirst($model->bacMember->bacPosition->position) : '' ?>
                </td>
            </tr>


        </table>
    </div>

</div>
<?php
?>
<style>
    th,
    td {
        border: 1px solid black;
        padding: 12px;
        font-size: 16;
    }


    table {
        width: 100%;
    }

    @media print {

        .btn,
        .main-footer,
        .position-select {
            display: none;
        }

    }
</style>
<?php
$items = $model->getItemsA();
SweetAlertAsset::register($this);
?>
<script>
    $(document).ready(function() {
        $("#final").click((e) => {
            e.preventDefault();
            let ths = $(e.target)
            let link = ths.attr('href');
            swal({
                title: "Are you sure you want to " + ths.text() + " this PR?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Confirm',
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: true,
                width: "500px",
                height: "500px",
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: link,
                        method: 'POST',
                        data: {
                            _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                        },
                        success: function(response) {
                            const res = JSON.parse(response)
                            if (!res.error) {
                                swal({
                                    title: 'Success',
                                    type: 'success',
                                    button: false,
                                    timer: 3000,
                                }, function() {
                                    location.reload(true)
                                })
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: res.message,
                                    button: false,
                                    timer: 5000,
                                })
                            }
                        },
                        error: function(error) {
                            console.error('Final failed:', error);
                        }
                    });
                }
            })

        });
        new Vue({
            el: '#main',
            data: {
                items: <?= !empty($items) ? json_encode($items) : [] ?>,
                bacPositionOptions: <?= json_encode(BacPosition::find()->asArray()->all()) ?>,
                position: ''
            },
            methods: {
                updatePosition() {
                    console.log('qwe')
                },
                formatDate(itemDate) {
                    const predefinedDate = new Date(itemDate); // Replace this with your predefined date

                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    const formattedDate = predefinedDate.toLocaleDateString(undefined, options);
                    return formattedDate
                },
                final(event) {
                    event.preventDefault()
                    const href = event.target.getAttribute('href');
                    swal({
                        title: "Are you sure you want to Final this NOP?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Confirm',
                        cancelButtonText: "Cancel",
                        closeOnConfirm: false,
                        closeOnCancel: true,
                        width: "500px",
                        height: "500px",
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: href,
                                method: 'POST',
                                data: {
                                    _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                                },
                                success: function(response) {
                                    const res = JSON.parse(response)
                                    if (!res.error) {
                                        swal({
                                            title: 'Success',
                                            type: 'success',
                                            button: false,
                                            timer: 3000,
                                        }, function() {
                                            location.reload(true)
                                        })
                                    } else {
                                        swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: res.message,
                                            button: false,
                                            timer: 5000,
                                        })
                                    }
                                },
                                error: function(error) {
                                    console.error('Final failed:', error);
                                }
                            });
                        }
                    })
                }

            }
        })
    })
</script>