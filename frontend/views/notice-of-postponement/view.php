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
$imagePath =  Yii::$app->request->baseUrl . '/frontend/web/dti_logo.png';

$approved_by = Employee::getEmployeeById($model->fk_approved_by);
$toDate = DateTime::createFromFormat('Y-m-d H:i:s', $model->to_date);


?>
<div class="notice-of-postponement-view" id="main">




    <div class="container card card-primary p-4">
        <p>
            <?php
            if (!$model->is_final) {
                echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) . ' ';
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
        </p>
        <table>

            <thead>
                <tr>
                    <td colspan="3" class="text-right no-bdr">
                        <?= Html::img('frontend/web/dtiNewLogo.png', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width:250px ;']); ?>
                    </td>
                </tr>
            </thead>
            <tr>
                <th colspan="3" class="ctr no-bdr">
                    <u style="font-size:larger;"> NOTICE OF POSTPONEMENT</u>
                </th>
            </tr>
            <tr>
                <td colspan="3" class="no-bdr">
                    <p class=""> <span class="ml-4"></span>Due to the <?= $model->type == 1 ? 'non-quorum of RBAC members' : 'short period of time prior to the opening' ?>
                        , Notice of Postponement is hereby issued for the following procurement activities pursuant to Section 29 of the 2016 Revised IRR of RA 9184, to wit:</p>
                </td>
            </tr>
            <tr>
                <th class="ctr">ACTIVITY</th>
                <td class="ctr"><u>From <br>(Date/Time)</u></td>
                <td class="ctr"><u>to <br>(Date/Time)</u></td>
            </tr>

            <tr v-for="item in items">
                <th class="">{{item.rfq_number}} - {{item.purpose}}</th>
                <td class="ctr">{{formatDate(item.from_date)}} 3:00 PM</td>
                <td class="ctr"><?= $toDate->format('F d, Y h:i A') ?> </td>
            </tr>
            <tr>
                <td colspan="3" class="no-bdr">
                    <p class="mt-5">
                        <span class="ml-4"></span> Deadline for the submission of quotation is on
                        <?= $toDate->format('F d, Y') . ' at ' . $toDate->format('h:i a') ?>
                        <b>Late bids/qoutations will not be accepted.</b> The BAC shall take custody of bids submitted on or beore the deadline of submission to ensure integrit, securit and confidentiality.
                    </p>
                    <p class="ml-4">For information of all concerned.</p>
                </td>
            </tr>
            <tr>
                <td colspan="" class="no-bdr"> </td>
                <td colspan="2" class="text-center no-bdr pt-5">

                    <u><b><?= !empty($approved_by['employee_name']) ? strtoupper($approved_by['employee_name']) : '' ?></b></u><br>
                    <p style="text-transform:capitalize;" class="pos">{{position}}</p>
                    <select class=" text-center position-select form-control" @change='updatePosition' v-model='position'>
                        <option value="">Select BAC Position</option>
                        <option v-for="option in bacPositionOptions" :value="option.position">{{ option.position }}</option>
                    </select>
                </td>
            </tr>

        </table>
    </div>

</div>
<?php
?>
<style>
    .no-bdr {
        border: 0;
    }

    th,
    td {
        border: 1px solid black;
        padding: 12px;
        font-size: 16;
    }

    .ctr {
        text-align: center;
    }

    .pos {
        display: none;
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

        .container {
            margin: 0 1in 0 .9in;

        }

        table {
            margin-right: 100px;
        }

        .pos {
            display: inline-block;
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