<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IirupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'IIRUPs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iirup-index">


    <p>
        <?= Html::a('Create IIRUP', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'IIRUPs'
        ],
        'columns' => [

            'serial_number',
            'fk_acctbl_ofr',
            'fk_approved_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>