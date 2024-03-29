<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RODs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rod-index">


    <p>
        <?= Html::a('Create Rod', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    $del = 'display:none;';
    if (Yii::$app->user->can('ro_accounting_admin')) {
        $del = '';
    }
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of RODs'
        ],
        'pjax'=>true,
        'columns' => [

            'rod_number',
            'province',
            [
                'label' => 'Fund Source',
                'value' => function ($model) {
                    $fund_sources = '';
                    foreach ($model->rodEntries as $i => $val) {
                        if ($i > 0) {
                            $fund_sources .= ',';
                        }
                        $fund_sources .= $val->advancesEntries->fund_source;
                    }
                    return $fund_sources;
                }
            ],

            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['style' => "$del"],
            ],
        ],
    ]); ?>


</div>