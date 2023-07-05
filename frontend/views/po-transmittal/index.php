<?php

use app\components\helpers\MyHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoTransmittalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Po Transmittals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-transmittal-index">


    <p>
        <?= Html::a('Create Po Transmittal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Transmittals',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [

            'transmittal_number',
            'date',
            'created_at',
            'is_accepted',

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