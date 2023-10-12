<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GeneralLedgerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'General Ledgers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="general-ledger-index">


    <p>
        <?= Yii::$app->user->can('create_general_ledger') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success ']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'General Ledgers'
        ],
        'columns' => [
            'reporting_period',
            [
                'label' => 'General Ledger',
                'attribute' => 'object_code',
                'value' => function ($model) {
                    $query = Yii::$app->db->createCommand("SELECT CONCAT(object_code,'-',account_title) as general_ledger FROM accounting_codes 
                    WHERE object_code = :object_code")
                        ->bindValue(':object_code', $model->object_code)
                        ->queryOne();
                    return !empty($query['general_ledger']) ? $query['general_ledger'] : '';
                }
            ],
            [
                'label' => 'Book',
                'attribute' => 'book_id',
                'value' => function ($model) {
                    return $model->book->name;
                }
            ],
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_general_ledger') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>