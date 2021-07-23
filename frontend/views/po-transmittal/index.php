<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoTransmittalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Po Transmittals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-transmittal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php

        if (Yii::$app->user->can('create_po_transmittal')) {
            echo Html::a('Create Po Transmittal', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
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
            ['class' => 'yii\grid\SerialColumn'],

            'transmittal_number',
            'date',
            'created_at',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>