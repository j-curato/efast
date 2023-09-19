<?php

use app\models\FundClusterCode;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RecordAllotmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Record Allotments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-allotments-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Record Allotments', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <form id="add_data" method="POST">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'document_recieve_id',
                'fund_cluster_code_id',
                // [
                //     'label' => 'Fund Cluster Code',
                //     'attribute' => 'fundClusterCode.name',
                //     'filter' => Html::activeDropDownList(
                //         $searchModel,
                //         'fund_cluster_code_id',
                //         ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
                //         ['class' => 'form-control', 'prompt' => 'Major Accounts']
                //     )
                // ],
                'financing_source_code_id',
                'fund_category_and_classification_code_id',
                //'authorization_code_id',
                //'mfo_pap_code_id',
                //'fund_source_id',
                //'reporting_period',
                //'serial_number',
                //'allotment_number',
                //'date_issued',
                //'valid_until',
                //'particulars',


                ['class' => 'yii\grid\ActionColumn'],
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        return ['value' => $model->id, 'onchange' => 'sample(this)'];
                    }
                ],
                [
                    'label' => 'Actions',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return ' ' . Html::input('text', "amount[]",);
                    }
                ]

            ],
        ]); ?>
        <input type="submit" name="submit">
    </form>
    <script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
    <script>
        function sample(qwe) {

            // // $('#'+qwe.value).prop('disabled', true);
            if (qwe.checked) {
                $(`:input[name="amount[${qwe.value}]"]`).prop('disabled', true);
                console.log(qwe.checked)
            } else {
                $(`:input[name="amount[${qwe.value}]"]`).prop('disabled', false);
            }

        }
        $(document).ready(function() {
            $(`:input[name="amount[]"]`).prop('disabled', true);
        })
    </script>

</div>

<?php
$script = <<< JS

$(document).ready(function(){
    let gen = undefined
    let book_id = undefined
    let reporting_period=undefined
    var title=""
    $(`:input[name="amount[]"]`).prop('disabled', true);
    $( ".checkbox" ).on('change keyup', function(){
        book_id = $(this).val()
        console.log($(this).val())
        // console.log(book_id)
        // query()
    })
    $("#reporting_period").change(function(){
        reporting_period=$(this).val()
        // query()
    })
    $('#su').click(function(){
        query()
    })
    function sample(){
        console.log(1)
    }
    function query(){
        // console.log(book_id+gen)
        // console.log(book_id)
        $.pjax({
        container: "#journal", 
        url: window.location.pathname + '?r=jev-preparation/general-journal',
        type:'POST',
        data:{
            book_id:book_id?book_id:0,
            reporting_period:reporting_period?reporting_period:'',
        }});
    }
    function thousands_separators(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
    $(document).ready(function(){
        $('#add_data').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: window.location.pathname + '?r=process-ors/sample',
                    method: "POST",
                    data: $('#add_data').serialize(),
                    success: function(data) {
                        console.log(data)
                    }
                });
            })
    })

})

JS;
$this->registerJs($script);
?>