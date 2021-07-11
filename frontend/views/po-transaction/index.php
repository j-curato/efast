<?php

use yii\helpers\Html;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', [
            'value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=po-transaction/create'),
            'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
        ]); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'List of Transactions'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tracking_number',

            'po_responsibility_center_id',
            'payee:ntext',
            'particular:ntext',
            'amount',
            //'payroll_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>


<?php
SweetAlertAsset::register($this);
$script = <<<JS
  
         $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });

JS;
$this->registerJs($script);
?>