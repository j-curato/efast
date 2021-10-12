<?php

use app\models\Books;
use app\models\DvAucs;
use app\models\DvAucsSearch;
use aryelds\sweetalert\SweetAlert;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-disbursement-form">

    <div class="container">


        <div class="row">
            <div class="col-sm-12" style="text-align:center;color:red">
                <h4 id="link">
                </h4>
            </div>
        </div>
        <form id="cash_disbursement_form">

            <div class="row">
                <div class="col-sm-3">
                    <label for="reporting_period"> Reporting Period</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'reporting_period',
                        'value' => !empty($model->reporting_period) ? $model->reporting_period : '',
                        'options' => [
                            'required' => true
                        ],
                        'pluginOptions' => [
                            'format' => "yyyy-mm",
                            'autoclose' => true,
                            'startView' => "year",
                            'minViewMode' => 'months'

                        ]
                    ])

                    ?>
                </div>



            </div>
            <div class="row">
                <?php
                $gridColumn = [

                    [

                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model) {
                            return ['value' => $model->id,  'style' => 'width:20px;', 'class' => 'checkbox', 'name' => 'disbursement_id'];
                        }
                    ],

                    [
                        "label" => "Book",
                        "attribute" => "book_id",
                        "value" => "book.name"
                    ],
                    'reporting_period',
                    'mode_of_payment',
                    'check_or_ada_no',
                    'ada_number',
                    'issuance_date',
                    [
                        'label' => "DV Number",
                        "attribute" => "dv_aucs_id",
                        'value' => 'dvAucs.dv_number'
                    ],
                    [
                        'label' => "Payee",
                        "attribute" => "dvAucs.payee.account_name"
                    ],
                    [
                        'label' => "Particular",
                        "attribute" => "dvAucs.particular"
                    ],
                    [
                        'label' => "Amount Disbursed",
                        'format' => ['decimal', 2],
                        'value' => function ($model) {
                            $query = (new \yii\db\Query())
                                ->select(["SUM(dv_aucs_entries.amount_disbursed) as total_disbursed"])
                                ->from('dv_aucs')
                                ->join("LEFT JOIN", "dv_aucs_entries", "dv_aucs.id = dv_aucs_entries.dv_aucs_id")
                                ->where("dv_aucs.id =:id", ['id' => $model->dv_aucs_id])
                                ->one();

                            return $query['total_disbursed'];
                        }
                    ],
                    [
                        'label' => 'Good/Cancelled',
                        'attribute' => 'is_cancelled',
                        'value' => function ($model) {
                            $model->is_cancelled ? $q = 'cancelled' : $q = 'Good';
                            return $q;
                        }
                    ],

                ];
                ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'type' => Gridview::TYPE_PRIMARY,
 
                    ],

                    'export' => false,
                    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                    'columns' => $gridColumn
                ]); ?>
            </div>

            <button type="button" name="" id="submit" class="btn btn-success" style="width: 100%;">Save</button>
        </form>

    </div>


    <style>
        .container {
            background-color: white;
            padding: 12px
        }

        .grid-view td {
            white-space: normal;
            width: 5rem;
            padding: 0;
        }
    </style>
</div>


<?php
SweetAlertAsset::register($this);

$script = <<< JS

JS;
$this->registerJs($script);
?>
<script>
    $("#submit").click(function(e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: window.location.pathname + "?r=cash-disbursement/cancel-disbursement",
            data: $('#cash_disbursement_form').serialize(),
            success: function(data) {
                // console.log(JSON.parse(data))
                var res = JSON.parse(data)
                if (res.isSuccess) {
                    swal({
                        title: 'Successfuly Cancelled',
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
                        text:res.error,
                        button: false,
                        timer: 3000,
                    })
                }


            }
        })
    })
</script>