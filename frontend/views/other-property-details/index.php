<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OtherPropertyDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Other Property Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="other-property-details-index">


    <p>
        <?= Html::a('Create Other Property Details', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Other Property Details'
        ],
        'columns' => [
            'office_name',
            'property_number',
            'article',
            'description',
            'uacs',
            'general_ledger',
            'salvage_value_prcnt',
            'useful_life',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>