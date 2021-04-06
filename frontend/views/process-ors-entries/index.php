<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProcessOrsEntriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Process Ors Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-entries-index">

    <h1><?= Html::encode($this->title) ?></h1>
<!-- 
    <p>
        <?= Html::a('Create Process Ors Entries', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <!-- RAOUDS ANG MODEL ANI. TRIP KO LANG -->
    <!-- NAA SA PROCESS ORS ENTRIES CONTROLLER SA INDEX NAKO GE CHANGE -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'heading'=>'<h3 class="panel-title"> Process Ors</h3>',
            'type'=>'primary',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i>Create Process Ors', ['create'], ['class' => 'btn btn-success']),
            'exportConfig' => [
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_PDF => false
            ],
        ],
   
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'reporting_period',
            // 'raoudEntries.chartOfAccount.general_ledger',
            [
                'label' => 'General Ledger',
                'value' => 'raoudEntries.chartOfAccount.general_ledger',
                // 'value' => 'processOrs.reporting_period'
            ],
            [
                'label' => 'Serial Number',
                'attribute' => 'processOrs.serial_number',
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
                    if (!empty($query['total'])) {
                        return $query['total'];
                    } else {
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
                    if ($query['total'] < $amount  && $amount > 0) {

                        $t = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/adjust&id=$model->id";
                        return ' ' . Html::a('', $t, ['class' => 'btn-xs btn-secondary fa fa-pencil-square-o']);
                    } else {
                        return "";
                    }
                    // return $query['total'];
                }
            ],
            // [
            //     'label' => 'Adjust',
            //     'format' => 'raw',
            //     'value' => function ($model) {



            //             $t = yii::$app->request->baseUrl . "/index.php?r=process-ors/view&id=$model->process_ors_id";
            //             return ' ' . Html::a('', $t, ['class' => 'btn-xs btn-success fa fa-pencil-square-o']);

            //             // return $query['total'];
            //     }
            // ],


            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>