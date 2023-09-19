<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrContractTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Contract Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-contract-type-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::button('<i class="fa fa-pencil-alt"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=pr-contract-type/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Contract Type'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'contract_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

<?php
$script = <<<JS
            var i=false;
        $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });


        
JS;
$this->registerJs($script);
?>