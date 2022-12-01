<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TravelOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Travel Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="travel-order-index ">


    <p>
        <?= Html::a('Create Travel Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Travel Orders'
        ],
        'columns' => [

            'to_number',
            'date',
            'destination:ntext',
            [
                'attribute' => 'purpose',
                'value' => function ($model) {
                    return preg_replace('#\[n\]#', "\n", $model->purpose);
                }
            ],
            [
                'attribute' => 'expected_outputs',
                'value' => function ($model) {
                    return preg_replace('#\[n\]#', "\n", $model->expected_outputs);
                }
            ],

            [
                'attribute' => 'fk_approved_by',
                'value' => function ($model) {


                    $suffix = !empty($model->approvedBy->suffix) ? ', ' . $model->approvedBy->suffix : '';

                    return $model->approvedBy->f_name . ' ' . $model->approvedBy->m_name[0] . '. ' . $model->approvedBy->l_name . $suffix;
                }
            ],
            [
                'attribute' => 'fk_budget_officer',
                'value' => function ($model) {
                    $suffix = !empty($model->budgetOfficer->suffix) ? ', ' . $model->budgetOfficer->suffix : '';

                    return $model->budgetOfficer->f_name . ' ' . $model->budgetOfficer->m_name[0] . '. ' . $model->budgetOfficer->l_name . $suffix;
                }
            ],

            [
                'attribute' => 'fk_recommending_approval',
                'value' => function ($model) {

                    $suffix = !empty($model->recommendingApproval->suffix) ? ', ' . $model->recommendingApproval->suffix : '';
                    $name = '';
                    if (!empty($model->fk_recommending_approval)) {
                        $model->recommendingApproval->f_name . ' ' .
                            $model->recommendingApproval->m_name[0] . '. '
                            . $model->recommendingApproval->l_name . $suffix;
                    }
                    return $name;
                }
            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        padding: 0;
    }
</style>