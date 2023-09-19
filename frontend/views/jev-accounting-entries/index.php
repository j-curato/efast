<?php

use app\models\JevAccountingEntriesSearch;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevAccountingEntriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jev Accounting Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-accounting-entries-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Jev Accounting Entries', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    $q = new JevAccountingEntriesSearch();
    $w = $q->search(Yii::$app->request->queryParams);
    $gridColumn = [
        'id',
        'jevPreparation.explaination',

    ];

    echo ExportMenu::widget([
        'dataProvider' => $w,
        'columns' => $gridColumn
    ]);
    ?>


    <!-- 
select jev_preparation.fund_cluster_code_id, jev_preparation.jev_number, jev_preparation.reporting_period ,
jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit

from jev_preparation,jev_accounting_entries where jev_preparation.id = jev_accounting_entries.jev_preparation_id
and jev_preparation.fund_cluster_code_id =1 and jev_accounting_entries.chart_of_account_id =1
GROUP BY jev_preparation.reporting_period
 -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Areas',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [

            'id',

            [
                'label' => 'Particular',
                'attribute' => 'jev_preparation_id',
                'value' => 'jevPreparation.explaination'
            ],
            [
                'label' => 'reporting period',
                'value' => 'jevPreparation.reporting_period'
            ],
            'debit',
            'credit',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>