<?php

use app\models\AdvancesEntriesForLiquidationSearch;
use app\models\AdvancesEntriesSearch;
use app\models\Payee;
use app\models\PoTransaction;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="liquidation-form">
    <div class="">
        <form id='save_data'>
            <?php
            $check_date = '';
            $check_number = '';
            $reporting_period = '';
            $check_range = '';
            $update_id = '';
            if (!empty($model)) {
                $check_date = $model->check_date;
                $check_number = $model->check_number;
                $reporting_period = $model->reporting_period;
                $check_range = $model->check_range_id;
                $update_id = $model->id;
            }
            echo "<input  type='hidden' value ='$update_id' name = 'update_id'/>";
            ?>
            <div class="row">
                <label for=" reporting_peirod">Reporting Period</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'reporting_period',
                    'id' => 'reporting_period',
                    'value' => $reporting_period,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'autoclose' => true,
                        'startView' => 'months',
                        'minViewMode' => 'months'
                    ],
                    'options' => [
                        'required' => true,
                        'readOnly' => true,
                        'style' => 'background-color:white'
                    ]
                ])
                ?>
            </div>
            <div class="row">
                    <label for=" check_date">Date</label>
                <?php
                echo DatePicker::widget([

                    'name' => 'check_date',
                    'id' => 'check_date',
                    'value' => $check_date,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true
                    ],
                    'options' => [
                        'required' => true, 'readOnly' => true,
                        'style' => 'background-color:white'
                    ]
                ])

                ?>


            </div>
            <div class="row">
                    <label for=" check_range">Check Range</label>
                <?php
                $province = strtolower(Yii::$app->user->identity->province);
                $q = PoTransaction::find();
                if (
                    $province === 'adn' ||
                    $province === 'ads' ||
                    $province === 'sds' ||
                    $province === 'sdn' ||
                    $province === 'pdi'
                ) {
                    $check = (new \yii\db\Query())
                        ->select([
                            'id',
                            "CONCAT(check_range.from,' to ',check_range.to) as range"
                        ])
                        ->from('check_range')
                        ->where('province =:province', ['province' => $province])
                        ->all();
                } else {
                    $check = (new \yii\db\Query())
                        ->select([
                            'id',
                            "CONCAT(check_range.from,' to ',check_range.to) as range"
                        ])
                        ->from('check_range')
                        ->all();
                }

                echo Select2::widget([
                    'data' => ArrayHelper::map($check, 'id', 'range'),
                    'name' => 'check_range',
                    'id' => 'check_range',
                    'value' => $check_range,
                    'pluginOptions' => [
                        'placeholder' => 'Select Range'
                    ],
                    'options' => [
                        // 'required' => true,
                    ]
                ])
                ?>
            </div>



            <div class="row">
                    <label for=" check_number">Check Number</label>

                <?php

                echo "<input type='number' class='form-control' id='check_number' required name='check_number' placeholder='Check Number' value='$check_number'/>
                    ";
                ?>
            </div>



            <?php
            $session = Yii::$app->session;

            // $form_token =$session['form_token'];

            echo "<input type='hidden' style='width:100%' value='{$session->get('form_token')}' name='token' />"
            ?>
            <button class="btn btn-success" id='save' type="submit">Save</button>
        </form>

    </div>

</div>
<style>
    table,
    tr {
        max-width: 100%;
    }

    td {
        padding: 5px;
    }

    .total_row {
        text-align: center;
        font-weight: bold;
    }

    .liquidation-form {
        padding: 2rem;
        background-color: white;
    }

    .grid-view td {
        white-space: normal;
        width: 10rem;
        padding: 0;
    }

    #add {
        width: 100%;
    }

    .form-control {
        border-radius: 5px;
    }

    #save {
        width: 100%;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
        border-left: none;
    }

    .select2-container .select2-selection--single {

        height: 34px;
    }

    .amount {
        margin-top: 15px;


    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this);

?>

<script>
    $(document).ready(function() {



    })
</script>


<?php
$script = <<<JS
    // ON change transction dropdown



    // SAVE DATA TO DATABASE
    $('#save_data').submit(function(e) {
        e.preventDefault();
        // $('#save').attr('disabled',true)
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=liquidation/cancelled-form',
            data: $('#save_data').serialize(),
            success: function(data) {
                console.log(data)
                var res = JSON.parse(data)
                console.log(res.id)
                // addToTransactionTable(res)
                if (res.isSuccess){
                    swal({
                        title:'Success',
                        type:'success',
                        button:false,

                    },function(){
                        location.reload()
                    }

       
                    )
                }
                else{
                    swal({
                        title:res.error,
                        type:'error',
                        button:false,

                    })
                }

            }
        })
    })

            
JS;
$this->registerJs($script);
?>