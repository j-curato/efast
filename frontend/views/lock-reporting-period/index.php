<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MajorAccountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lock Reporting Period';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="major-accounts-index">
    <form id="lock">

        <?php
        $data = [
            'process_ors' => 'Process Ors',
            'dv_aucs' => "DV AUCS",
            'cash_disbursement' => 'Cash Disbursement',
            'cash_reciept' => 'Cash Reciept',
            'record_allotment' => 'Record Allotment',
            'process_burs' => 'Process BURS',
            'jev' => 'Jev'
        ];
        echo Select2::widget([
            'attribute' => 'state_2',
            'name' => 'data',
            'id' => 'data',
            'data' => $data,

            'options' => ['placeholder' => 'Select a state ...', 'multiple' => true,],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

        ?>
        <label for="reporting_period">Reporting Period</label>
        <?php

        echo DatePicker::widget([

            'name' => 'reporting_period',
            'readonly' => true,
            'options' => [
                'style' => 'background-color:white'
            ],

            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm',
                'minViewMode' => 'months',
            ]
        ])

        ?>



        <button class="btn btn-success" type="submit">SAVE</button>

    </form>
</div>
<script>
    $('#lock').submit((e)=>{
        e.preventDefault()
        $.ajax({
            type:'POST',
            url:window.location.pathname + '?r=lock-reporting-period/insert',
            data:$('#lock').serialize(),
            success:function(data){
                console.log(data)
            }
        })
    })
</script>
<?php


$js = <<< JS

    $('#q').change(()=>{

        
        console.log( $('#q').val())
    })
JS;

$this->registerJs($js);

?>