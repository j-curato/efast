<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\Office;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\JsExpression;

$this->title = 'User Property Clearance';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-properties card" style="background-color: white;padding:1rem">




    <form id="filter">

        <div class="row">
            <?php

            if (Yii::$app->user->can('super-user')) {

            ?>
                <div class="col-sm-3">
                    <label for="actbl_ofr">Province</label>
                    <?= Select2::widget([
                        'name' => 'office',
                        'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                        'pluginOptions' => [
                            'allowClear' => true,

                        ],

                    ]) ?>
                </div>
            <?php } ?>

            <div class="col-sm-3">
                <label for="actbl_ofr">Accountable Officer</label>
                <?= Select2::widget([
                    'name' => 'actbl_ofr',
                    'options' => ['placeholder' => 'Search for a Employee ...'],
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
                            'data' => new JsExpression('function(params) { return {q:params.term,page:params.page||1}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>
            <div class="col-sm-3">
                <label for="act_usr_id">Actual User</label>

                <?= Select2::widget([
                    'name' => 'act_usr_id',
                    'options' => ['placeholder' => 'Search for a Employee ...'],
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
                            'data' => new JsExpression('function(params) { return {q:params.term,page:params.page||1}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>
            <div class="col-sm-1">
                <?= Html::submitButton('Generate', ['class' => 'btn btn-success', 'style' => 'margin-top:2rem']) ?>

            </div>
        </div>
    </form>
    <div class="con" id="con">
        <!-- 
        <table>
            <thead>
                <th>Property Card No.</th>
                <th>PAR No.</th>
                <th>Property No.</th>
                <th>Article</th>
                <th>Description</th>
                <th>Serial Number</th>
                <th>Date Acquired</th>
                <th>Acquisation Cost</th>
                <th>Serviceable/UnSeviceable</th>

                <th>Accountable Officer</th>
                <th>Actual User</th>
            </thead>
            <tbody></tbody>
        </table> -->

    </div>


    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<style>
    th,
    td {
        padding: 8px;
        border: 1px solid black;
        text-align: center;
    }

    table {
        width: 100%;
        margin-top: 2rem;
    }

    @media print {

        .main-footer,
        #filter {
            display: none;
        }

        th,
        td {
            padding: 3px;
            font-size: 10px;
        }
    }
</style>
<?php

$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depeneds' => [JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css");

?>
<script>
    function display(data) {
        $('#data_table tbody').html('')
        $.each(data, (key, accountable) => {
            let r = `
            <table>
            <thead>
            <tr>
                <th colspan='13'>${key}</th>
            </tr>
                <th>Property Card No.</th>
                <th>PAR No.</th>
                <th>Property No.</th>
                <th>Article</th>
                <th>Description</th>
                <th>Serial Number</th>
                <th>Date Acquired</th>
                <th>Acquisation Cost</th>
                <th>Serviceable/UnSeviceable</th>
                <th>Accountable Officer</th>
                <th>Actual User</th>
                <th>location</th>
                <th>Remarks</th>
            </thead>
            <tbody>`;
            $.each(accountable, (key2, val) => {

                r += `<tr>
                <td>${val.pc_num}</td>
                <td>${val.par_number}</td>
                <td>${val.property_number}</td>
                <td>${val.article_name}</td>
                <td>${val.description}</td>
                <td>${val.serial_number}</td>
                <td>${val.date_acquired}</td>
                <td>${thousands_separators(val.acquisition_amount)}</td>
                <td>${val.isServiceable}</td>
                <td>${val.actble_ofr}</td>
                <td>${val.actual_user}</td>
                <td>${val.location}</td>
                <td style='width:150px'></td>
            </tr>`

            })
            r += `</tbody></table>`
            $('#con ').append(r)
            $('#con').append(`<p style='page-break-after:always;'></p>`)

        })

    }
    $(document).ready(() => {
        $('#filter').submit((e) => {
            $('#dots5').show()
            $('.con').hide()

            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: $('#filter').serialize(),
                success: (data) => {
                    $('#dots5').hide()
                    $('.con').show()
                    const res = JSON.parse(data)

                    if (res) {
                        display(res)
                    }
                }
            })
        })
    })
</script>