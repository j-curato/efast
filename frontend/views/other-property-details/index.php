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

    <h1><?= Html::encode($this->title) ?></h1>

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

            [
                'attribute' => 'fk_property_id',
                'value' => 'property.property_number'
            ],
            [
                'attribute' => 'fk_chart_of_account_id',
                'value' => function ($model) {
                    return !empty($model->fk_chart_of_account_id) ? $model->chartOfAccount->uacs . '-' . $model->chartOfAccount->general_ledger : '';
                }
            ],

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