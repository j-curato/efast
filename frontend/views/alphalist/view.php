<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Alphalist */

$this->title = $model->alphalist_number;
$this->params['breadcrumbs'][] = ['label' => 'Alphalists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$id = $model->id;
?>
<div class="alphalist-view">


    <p>
        <?php

        if ($model->status === 9) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }
        if (Yii::$app->user->can('super-user')) {

            if ($model->status === 9) {
                echo "<button class='btn btn-success final' style='margin:5px'>Final</button>";
            } else {
                echo "<button class='btn btn-danger final' style='margin:5px'>Draft</button>";
            }
        }
        ?>

    </p>
    <div class="container">
        <div style="text-align: right; width:100%">
            <?php echo $this->title ?>
        </div>
        <table class="" id="conso_table">
            <tbody></tbody>
        </table>
        <table class="" id="detailed_table" style="margin-top: 5rem;">
            <thead>
                <th>DV Number</th>
                <th>Check Date</th>
                <th>Check Number</th>
                <th>Payee</th>
                <th>Gross Amount</th>
                <th>Withdrawals</th>
                <th>Liquidation Damages</th>
                <th>Total Sales Tax (VAT/Non-VAT)</th>
                <th>Income Tax (Expanded Tax)</th>
                <th>Total Tax</th>
            </thead>
            <tbody></tbody>
        </table>

        <div style="margin-top: 5rem;">
            <span>Certified Correct By:</span>

        </div>
        <div style="text-align: center; width:30rem">

            <br>
            <span style="font-weight: bold;">
                <?php
                $prov =  Yii::$app->memem->cibrCdrHeader($model->province);
                echo $prov['officer'];
                ?>
            </span>
            <br>
            <span>Signature Over Printed Name</span>
            <br>


            <span style="margin-right:150px;">Date: </span>
        </div>

    </div>

</div>
<style>
    .container {
        background-color: white;
        padding: 3rem;
    }

    #detailed_table {
        width: 100%;
    }

    #conso_table {
        width: 50%;
    }

    th,
    td {
        border: 1px solid black;
        padding: .8rem;
        text-align: center;
    }

    .amount {
        text-align: right;
    }

    @media print {

        th,
        td {
            padding: 3px;
            font-size: 10px;
        }

        .container {
            padding: 0;

        }

        .btn {
            display: none;
        }

        .main-footer {
            display: none;
        }

        .total {
            font-weight: bold;
        }
    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/alphalistJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this);
?>
<script>
    $(document).ready(function() {

        const res = JSON.parse(<?php echo json_encode($res) ?>);

        $('#conso_table tbody').html('')
        $('#detailed_table tbody').html('')

        displayConsoHead(res.r)
        displayConso(res.conso, res.r)
        displayDetailed(res.detailed)
        $('.final').click(function(e) {
            e.preventDefault()
            swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    console.log('<?php echo $id ?>')
                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            url: window.location.pathname + "?r=alphalist/final",
                            data: {
                                id: '<?= $model->id ?>'
                            },
                            success: function(data) {
                                var res = JSON.parse(data)
                                var cancelled = "Successfuly Activated";
                                if (res.isSuccess) {
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
                                        title: "Error ",
                                        type: 'error',
                                        button: false,
                                        timer: 3000,
                                    })
                                }

                            }
                        })


                    }
                })
        })
    })
</script>