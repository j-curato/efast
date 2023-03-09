<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RemittanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Remittances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remittance-index">


    <p>
        <?= Html::a('Create Remittance', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Remittance'
        ],
        'columns' => [

            'remittance_number',
            'reporting_period',

            [
                'attribute' => 'payee_id',
                'value' => 'payee.account_name'
            ],
            [
                'label' => 'Book',
                'attribute' => 'book_id',
                'value' => 'book.name'
            ],

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id, 'none');
                }
            ],
        ],
    ]); ?>


</div>