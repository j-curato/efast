<?php

use app\components\helpers\MyHelper;
use app\models\Books;
use app\models\ChartOfAccounts;
use app\models\Office;
use app\models\PpeCondition;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Rpcppe */
/* @var $form yii\widgets\ActiveForm */

$uacs = [];
$actble = [];
if (!empty($model->fk_actbl_ofr)) {

    $actble = ArrayHelper::map(MyHelper::getEmployee($model->fk_actbl_ofr), 'employee_id', 'employee_name');
}
if (!empty($model->fk_chart_of_account_id)) {
    $qry = YIi::$app->db->createCommand("SELECT CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger) as uacs, chart_of_accounts.id FROM chart_of_accounts WHERE 
    id =:id")
        ->bindValue(':id', $model->fk_chart_of_account_id)
        ->queryAll();
    $uacs = ArrayHelper::map($qry, 'id', 'uacs');
}

?>

<div class="rpcppe">


    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
    ]); ?>


    <div class="row">
        <?php

        if (Yii::$app->user->can('super-user')) {

        ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Office'
                    ]
                ])
                ?>
            </div>
        <?php } ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm',
                    'minViewMode' => 'months',
                    'autoclose' => true,
                ]
            ])
            ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'fk_book_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Book'
                ]
            ])
            ?>
        </div>

        <div class="col-sm-3">
            <?= $form->field($model, 'fk_chart_of_account_id')->widget(Select2::class, [
                'data' => $uacs,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=other-property-details/search-chart-of-accounts',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(property_number) { return property_number.text; }'),
                    'templateSelection' => new JsExpression('function (property_number) { return property_number.text; }'),
                ],

            ]) ?>
        </div>

        <div class="col-sm-3">
            <?= $form->field($model, 'fk_actbl_ofr')->widget(Select2::class, [
                'data' => $actble,
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
        <div class="col-sm-2">
            <label for="generate">Actions</label>
            <br>
            <button id="generate" class="btn btn-primary" type="button">Generate</button>
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>


    </div>



    <?php ActiveForm::end(); ?>


    <div id="con">

    </div>
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<style>
    .rpcppe-foot-wrp {
        overflow: hidden;
        /* clearfix */
    }

    .rpcppe-foot-col {
        width: 33.33%;
        float: left;
    }

    .ctr {
        text-align: center;
    }

    .rpcppe {
        background-color: white;
        padding: 20px;
    }

    table {
        width: 100%;
        padding: 2rem;
    }

    th,
    td {
        border: 1px solid black;
        padding: 10px;
    }

    @media print {

        .main-footer,
        .btn,
        #Rpcppe {
            display: none;
        }

        th,
        td {
            padding: 3px;
            font-size: x-small;
        }
    }
</style>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile("@web/js/moment.min.js", ['depends' => [JqueryAsset::class]]);
$this->registerCssFile("@web/frontend/web/css/site.css");
$this->registerJsFile("@web/frontend/views/rpcppe/rpcppeScript.js", ['depends' => [JqueryAsset::class]]);
?>
<script>
    $(document).ready(() => {

        $('#generate').click(() => {
            $('#con').html('')
            $('#dots5').show()
            $.ajax({
                type: "POST",
                url: window.location.pathname + '?r=rpcppe/generate',
                data: {
                    book_id: $('#rpcppe-fk_book_id').val(),
                    emp_id: $('#rpcppe-fk_actbl_ofr').val(),
                    uacs_id: $('#rpcppe-fk_chart_of_account_id').val(),
                    office_id: $('#rpcppe-fk_office_id').val(),
                    reporting_period: $('#rpcppe-reporting_period').val()
                },
                success: function(data) {
                    var res = JSON.parse(data)
                    let act_ttl = $('#rpcppe-fk_chart_of_account_id :selected').text()
                    let period = $('#rpcppe-reporting_period').text()
                    let book_name = $('#rpcppe-fk_book_id :selected').text();
                    let endDateMoment = moment($('#rpcppe-reporting_period').val());
                    period = endDateMoment.format("MMMM, YYYY")
                    setTimeout(() => {
                        $('#dots5').hide()
                        display(res, act_ttl, period, book_name)
                    }, 1100)

                }
            })
        })
    })
</script>