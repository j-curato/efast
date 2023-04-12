<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Rpcppe */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rpcppes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$uacs =  $model->chartOfAccount->uacs . '-' . $model->chartOfAccount->general_ledger;
$period  = DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F, Y');
$book_name = $model->book->name;
?>
<div class="rpcppe-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div id="con">


    </div>

</div>
<style>
    #con {
        background-color: white;
    }

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
        display(<?= json_encode($res); ?>, '<?= $uacs; ?>', '<?= $period; ?>', '<?=$book_name?>')
    })
</script>