<?php

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

            'fk_property_id',
            'depreciation_schedule',
            'fk_chart_of_account_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>