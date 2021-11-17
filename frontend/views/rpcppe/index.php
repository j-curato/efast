<?php

use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RpcppeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RPCPPE';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rpcppe-index">
    <p>
        <?= Html::a('Create Rpcppe', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>Gridview::TYPE_PRIMARY,
            'heading'=>'RPCPPE'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'rpcppe_number',
            'reporting_period',
            'book_id',
            'certified_by',
            'approved_by',
            'verified_by',
            //'verified_pos',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>