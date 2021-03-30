<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProcessBursSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Process Burs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-burs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Process Burs', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <!-- AND MODEL ANI NAA KAY SA RAOUDS NAA SA CONROLLER INDEX NAKO GE CHANGE -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'raoudEntries.chartOfAccount.general_ledger',
            [
                'label' => 'Serial Number',
                'attribute' => 'processOrs.reporting_period',
                // 'value' => 'processOrs.reporting_period'
            ],
            [
                'label' => 'Amount',
                'attribute' => 'raoudEntries.amount'
            ],
            [
                'label' => 'Adjust Amount',
                'value' => function ($model) {
                    $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total
                    FROM `raouds`,raoud_entries
                    WHERE raouds.id=raoud_entries.raoud_id
                    AND raoud_entries.amount >0
                    AND raoud_entries.parent_id_from_raoud = $model->id
                     ")->queryOne();
                    if (!empty($query['total'])){
                        return $query['total'];

                    }
                    else{
                        return '';
                    }
                }
            ],

            [
                'label' => 'Adjust',
                'format' => 'raw',
                'value' => function ($model) {

                    $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total
                    FROM `raouds`,raoud_entries
                    WHERE raouds.id=raoud_entries.raoud_id
                    AND raoud_entries.amount >0
                    AND raoud_entries.parent_id_from_raoud = $model->id
                     ")->queryOne();
                    $amount = $model->raoudEntries->amount;
                    if ( $query['total'] <$amount  && $amount >0) {

                        $t = yii::$app->request->baseUrl . "/index.php?r=process-burs/update&id=$model->id";
                        return ' ' . Html::a('', $t, ['class' => 'btn btn-success fa fa-pencil-square-o']);
                    }
                    else{
                        return ""  ;
                    }
                        // return $query['total']; 
                }
            ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
