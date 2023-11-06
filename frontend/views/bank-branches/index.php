<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BankBranchesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bank Branches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-branches-index">

    <p>
        <?= Yii::$app->user->can('update_bank_branches') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Branches'
        ],
        'columns' => [

            'fk_bank_id',
            'branch_name:ntext',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_bank_branches') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>

<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    [
        'depends' => [JqueryAsset::class]
    ]
);

?>