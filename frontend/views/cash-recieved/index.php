<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashRecievedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Recieveds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-recieved-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <!-- <?= Html::a('Create Cash Recieved', ['create'], ['class' => 'btn btn-success']) ?> -->
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Add New', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=cash-recieved/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
        ],
        'export'=>false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'document_recieved_id',
            'book_id',
            'mfo_pap_code_id',
            'date',


            //'reporting_period',
            //'nca_no',
            //'nta_no',
            //'nft_no',
            //'purpose',
            //'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

<?php
$script = <<< JS

    $('#modalButtoncreate').click(function(){
         $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
    });
    $('.modalButtonedit').click(function(){
        $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
    });
JS;
$this->registerJs($script);
?>