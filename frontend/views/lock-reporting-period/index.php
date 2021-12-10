<?php

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

    <?php
    $data = [
        'process_ors'=>'Process Ors', 
        'dv_aucs'=>"DV AUCS",
         'cash_disbursement'=>'Cash Disbursement',
         'cash_reciept'=>'Cash Reciept',
         'record_allotment'=> 'Record Allotment',
         'process_burs'=>'Process BURS',
         'jev'=>'Jev'
        ];
    echo Select2::widget([
        'attribute' => 'state_2',
        'name' => 'q',
        'id' => 'q',
        'data' => $data,

        'options' => ['placeholder' => 'Select a state ...', 'multiple' => true,],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>


    <form>



    </form>
</div>

<?php

$js = <<< JS

    $('#q').change(()=>{

        
        console.log( $('#q').val())
    })
JS;

$this->registerJs($js);

?>