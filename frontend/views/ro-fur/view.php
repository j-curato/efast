<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php


/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;

$this->title = "FUR";
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="dots5">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
<div class="jev-preparation-index ">
    <div class="container">
        <?php

        $user = Yii::$app->user->can('super-user');
        if ($user) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }
        ?>
        <table class="" id="fur_table" style="margin-top: 30px;">
            <thead>
                <tr>
                    <th>Division</th>
                    <th style="width: 250px;">MFO/PAP </th>
                    <th>Account</th>
                    <th>Beginning Balance</th>
                    <th>Allotment Recieved</th>
                    <th>Obligation Incured</th>
                    <th> Balance</th>
                    <th> FUR%</th>
                </tr>


            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


</div>
<style>
    .container {
        background-color: white;
        padding: 3rem;
    }

    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
    }


    /* #con {
        display: none;
    } */

    .jev-preparation-index {
        display: none;
    }

    @media print {
        #summary_table {
            margin-top: 0;
        }

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        .row {
            display: none
        }

        .main-footer {
            display: none;
        }

        .panel {
            padding: 0;
        }

        .container {
            padding: 0;
        }

        .btn {
            display: none;
        }
    }
</style>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/module_js_css/roFur/ro-fur.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    var mfo = []
    var allotment_balances = []

    $(document).ready(function() {
        var res = JSON.parse(<?php echo $dataProvider ?>)
        mfo = res.mfo_pap
        allotment_balances = res.allotments
        setTimeout

        setTimeout(function() {
            addData(res.result)
            $('#dots5').hide()
            $('.jev-preparation-index').show()
        }, 1000)
    })
</script>