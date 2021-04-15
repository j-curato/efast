<?php

use app\models\Books;
use app\models\DvAucsEntries;
use app\models\DvAucsEntriesSearch;
use app\models\DvAucsSearch;
use app\models\RaoudsSearchForProcessOrsSearch;
use aryelds\sweetalert\SweetAlert;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-disbursement-form">


    <?php
    $dv_aucs_entries_id = !empty($model->dv_aucs_entries_id) ? $model->dv_aucs_entries_id : "";

    $is_cancelled = $model->is_cancelled;
    ?>
    <div class="container">


        <form id="cash_disbursement_form">

            <div class="row">
                <?= Html::input('text', 'update_id', !empty($model->id) ? $model->id : '', [
                    "style" => "display:none"
                ]) ?>

                <div class="col-sm-3">
                    <label for="reporting_period"> Reporting Period</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'reporting_period',
                        'value' => !empty($model->reporting_period) ? $model->reporting_period : '',
                        'pluginOptions' => [
                            'format' => "yyyy-mm",
                            'autoclose' => true,
                            'startView' => "year",
                            'minViewMode' => 'months'

                        ]
                    ])

                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="issuance_date"> Issuance Date</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'issuance_date',
                        'value' => !empty($model->issuance_date) ? $model->issuance_date : '',
                        'pluginOptions' => [
                            'format' => "mm-dd-yyyy",
                            'autoclose' => true,

                        ]
                    ])

                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="book">Books</label>
                    <?php

                    echo Select2::widget([
                        'name' => "book",
                        'value' => !empty($model->book_id) ? $model->book_id : '',
                        'data' => ArrayHelper::map(Books::find()->asArray()->all(), "id", "name"),
                        'options' => [
                            'placeholder' => "Select Book"
                        ]
                    ])
                    ?>
                </div>

            </div>
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-sm-3">
                    <label for="check_ada_no">ADA/Check Number</label>
                    <?php
                    // echo "

                    // <input type='text' class='form-control' name='check_ada_no' value='".!empty($model->check_or_ada_number)?$model->check_or_ada_no:''."'>
                    // ";
                    ?>
                    <?= Html::input('text', 'check_ada_no', !empty($model->check_or_ada_no) ? $model->check_or_ada_no : '', ['class' => 'form-control']) ?>
                </div>

                <div class="col-sm-3">
                    <label for="good_cancelled"> Good/Cancelled</label>
                    <?php
                    echo Select2::widget([
                        'data' => [0 => "Good", 1 => "Cancelled"],
                        'value' => $is_cancelled,
                        "name" => "good_cancelled",
                        "options" => [
                            "autoclose" => true,
                            "placeholder" => "Good/Cancelled"
                        ]

                    ])
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="mode_of_payment">Mode of Payment</label>
                    <?php

                    echo Select2::widget([
                        'name' => "mode_of_payment",
                        'value' => !empty($model->mode_of_payment) ? $model->mode_of_payment : '',
                        'data' => ['check' => "Check", 'ada' => "ADA"],
                        "options" => [
                            "placeholder" => "Select Mode of Payment"
                        ]
                    ])
                    ?>
                </div>

            </div>




            <!-- <div class="col-sm-3" style="height:60x">
            <label for="book">Book</label>
            <select id="book" name="book" class="book select" style="width: 100%; margin-top:50px" required>
                <option></option>
            </select>
        </div> -->
            <?php
            $searchModel = new DvAucsEntriesSearch();
            $searchModel->id = $dv_aucs_entries_id;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->sort = ['defaultOrder' => ['id' => 'DESC']];

            $qwe = DvAucsEntries::find()->select(['id'])->all();
            $x = [];;
            foreach ($qwe as $v) {
                $x[] = $v->id;
            }
            // ob_clean();
            // echo "<pre>";
            // var_dump($qwe);
            // echo "</pre>";
            // return ob_get_clean();
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,

                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => "List Of DV's",
                ],

                'toggleDataOptions' => ['maxCount' => 100],
                'pjax' => true,
                'export' => false,
                'floatHeaderOptions' => [
                    'top' => 50,
                    'position' => 'absolute',
                ],

                'columns' => [

                    // 'id',

                    [
                        "label" => "id",
                        "attribute" => "id",
                        // "filter" => function () use ($dv_aucs_entries_id) {
                        //     return $dv_aucs_entries_id;
                        // }
                    ],

                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) use ($x, $dv_aucs_entries_id) {
                            // return ['value' => $model->id,  'style' => 'width:20px;', 'class' => 'checkbox'];
                            $bool = in_array($dv_aucs_entries_id, $x);
                            if ($dv_aucs_entries_id === $model->id) {
                                return ['checked' => $bool];
                            }
                        }
                    ],
                    [
                        'label' => 'DV Number',
                        'attribute' => 'dv_aucs_id',
                        'value' => "dvAucs.dv_number"
                        // 'filter' => Html::activeDropDownList(
                        //     $searchModel,
                        //     'recordAllotment.fund_cluster_code_id',
                        //     ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
                        //     ['class' => 'form-control', 'prompt' => 'Major Accounts']
                        // )

                    ],
                    [
                        'label' => 'Amount',
                        'attribute' => 'amount_disbursed',
                        'filter' => false,
                        'format' => ['decimal', 2]
                    ],
                    [
                        'label' => 'Payee',
                        'attribute' => 'dvAucs.payee.account_name'
                    ],
                    [
                        'label' => 'Particular',
                        'attribute' => 'dvAucs.particular',

                    ],





                ],
            ]); ?>
            <button type="button" name="" id="submit" class="btn btn-success">Submit</button>
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
    $(document).ready(function(){

        // $.getJSON(window.location.pathname +" ?r=")

        $("#submit").click(function(e){
            e.preventDefault();
            
            $.ajax({
                type:"POST",
                url:window.location.pathname + "?r=cash-disbursement/insert-cash-disbursement",
                data:$('#cash_disbursement_form').serialize(),
                success:function(data){
                    console.log(JSON.parse(data))
                    var res = JSON.parse(data)

                    if (res.isSuccess){
                        swal({
                            title:"Success",
                            type:'success',
                            button:false,
                            timer:3000,
                        })
                    }
                }
            })
        })
    })

JS;
$this->registerJs($script);
?>