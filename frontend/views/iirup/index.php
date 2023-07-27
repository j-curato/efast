<?php

use app\components\helpers\MyHelper;
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
        'pjax'=>true,
        'columns' => [

            'serial_number',
            'office_name',
            'approved_by',
            'accountable_officer',

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