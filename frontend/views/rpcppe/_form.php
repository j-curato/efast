<?php

use app\models\Books;
use app\models\PpeCondition;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rpcppe */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rpcppe">


    <form id='rpcppe_form'>
        <?php
        $certified_by = '';
        $aprroved_by = '';
        $certified_id = '';
        $aprroved_by_id = '';
        $verified_by = '';
        $reporting_period = '';
        $rpcppe_id= '';
        $book = '';
        if (!empty($model->rpcppe_number)) {
            $rpcppe_id=$model->rpcppe_number;
            $certified_by_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
                ->bindValue(':id', $model->certified_by)
                ->queryAll();
            $certified_by = ArrayHelper::map($certified_by_query, 'employee_id', 'employee_name');
            $aprroved_by_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
                ->bindValue(':id', $model->approved_by)
                ->queryAll();
            $aprroved_by = ArrayHelper::map($aprroved_by_query, 'employee_id', 'employee_name');
            $certified_id = [$model->certified_by];
            $aprroved_by_id = [$model->approved_by];
            $verified_by = $model->verified_by;
            $reporting_period  = $model->reporting_period;
            $book  = $model->book_id;
        }
        echo "<input type='hidden' value='$rpcppe_id' name='rpcppe_id'>";
        ?>

        <div class="row">
            <div class="col-sm-3">
                <label for="reporting_period">Reporting Period</label>
                <?php

                echo DatePicker::widget([
                    'id' => 'reporting_period',
                    'name' => 'reporting_period',
                    'value' => $reporting_period,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'minViewMode' => 'months',
                        'autoclose' => true,
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <label for="book_id">Book</label>
                <?php

                echo Select2::widget([
                    'name' => 'book_id',
                    'id' => 'book_id',
                    'value' => $book,
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-3">

                <label for="ppe_codition">PPE Condition</label>
                <?php
                echo Select2::widget([
                    'name' => 'ppe_condition',
                    'id' => 'ppe_condition',
                    'data' => ArrayHelper::map(PpeCondition::find()->asArray()->all(), 'id', 'condition'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Condition'
                    ]
                ])
                ?>
            </div>

        </div>


        <div class="row">
            <div class="col-sm-4">
                <label for="certified_by">Certified By</label>
                <?php echo  Select2::widget([
                    'data' => $certified_by,
                    'name' => 'certified_by',
                    'value' => $certified_id,
                    'options' => ['placeholder' => 'Search Employee ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=employee/search-employee',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>
            <div class="col-sm-4">
                <label for="aprroved_by">Approved By</label>
                <?php echo  Select2::widget([
                    'data' => $aprroved_by,
                    'name' => 'aprroved_by',
                    'value' => $aprroved_by_id,
                    'options' => ['placeholder' => 'Search Employee ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=employee/search-employee',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>
            <div class="col-sm-4">
                <label for="verified_by">Verified By</label>
                <input type="text" class="form-control" name="verified_by" value='<?php echo $verified_by ?>'>
            </div>
        </div>
        <button type='submit' class="btn btn-success">Save</button>
    </form>
    <button id="generate" class="btn btn-primary">Generate</button>
    <table  class="table table-hover">
        <thead>


        </thead>

    </table>
    <table class="table table-hover" id="rpcppe_table">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Property Number</th>
                <th>Article</th>
                <th>Model</th>
                <th>Serial Number</th>
                <th>Quantity</th>
                <th>Acquisition Amount</th>
                <th>PTR Number</th>
                <th>Transfer Type</th>
            </tr>

        </thead>
        <tbody>

        </tbody>
    </table>

</div>
<style>
    .rpcppe {
        background-color: white;
        padding: 20px;
    }
</style>
<script>
    $('#generate').click(() => {
        $.ajax({
            type: "POST",
            url: window.location.pathname + '?r=rpcppe/generate',
            data: {
                book_id: $('#book_id').val(),
                ppe_condition: $('#ppe_condition').val()
            },
            success: function(data) {
                var res = JSON.parse(data)
                console.log(res)
                var tbl = $('#rpcppe_table tbody')
                tbl.html('')
                for (var i = 0; i < res.length; i++) {

                    tbl.append(`<tr>
                     <td>${res[i]['employee_name']}</td>
                     <td>${res[i]['property_number']}</td>
                     <td>${res[i]['article']}</td>
                     <td>${res[i]['model']}</td>
                     <td>${res[i]['serial_number']}</td>
                     <td>${res[i]['unit_of_measure_id']}</td>
                     <td>${res[i]['acquisition_amount']}</td>
                     <td>${res[i]['ptr_number']}</td>
                     <td>${res[i]['transfer_type']}</td>
                     </tr>`)

                }
            }
        })
    })
</script>

<?php
$js = <<<JS
    $('#rpcppe_form').submit((e)=>{
        e.preventDefault()
        $.ajax({
            type:'POST',
            url:window.location.pathname + '?r=rpcppe/insert',
            data:$('#rpcppe_form').serialize(),
            success:function(data){
                console.log(data)
            }
        })
    })

JS;

$this->registerJs($js);

?>