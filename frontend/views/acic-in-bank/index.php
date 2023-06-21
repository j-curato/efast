<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AcicInBankSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acic In Banks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="acic-in-bank-index">


    <p>
        <?= Html::a('Create Acic In Bank', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'ACIC in Bank'
        ],
        'pjax'=>true,
        'columns' => [

            'serial_number',
            'date',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id, false);
                }
            ],
        ],
    ]); ?>


</div>