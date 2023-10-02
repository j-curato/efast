<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProccessOrsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$type_dis = strtoupper($type);
$this->title = 'Process ' . $type_dis;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-index">

    <p>
        <?php
        if ($type === 'burs') {
            echo  Html::a('Create Process ' . $type_dis, ['create-burs'], ['class' => 'btn btn-success']);
        } else {
            echo Html::a('Create Process ' . $type_dis, ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>
    <?= $this->render('export_form', ['orsType' => $type]) ?>
    <!-- ANG MODEL ANI KAY SA PROCESS ORS ENTRIES -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Process ' . $type_dis
        ],

        'columns' => [
            'serial_number',
            'reporting_period',
            'date',
            'tracking_number',
            'particular',
            'r_center',
            'payee',
            'type',
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
<script>
    // $(document).ready(() => {
    //     $('#ors').submit((e) => {
    //         e.preventDefault()
    //         $.ajax({
    //             type: 'POST',
    //             url: window.location.pathname + "?r=process-ors/export",
    //             data: $('#ors').serialize(),
    //             success: (data) => {
    //                 window.open(JSON.parse(data))
    //             }
    //         })

    //     })
    // })
</script>