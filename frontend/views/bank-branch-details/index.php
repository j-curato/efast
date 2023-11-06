<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BankBranchDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bank Branch Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-branch-details-index">

    <p>
        <?= Yii::$app->user->can('create_ro_general_journal') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'primary', 'heading' => 'Branch Details'],
        'columns' => [

            'fk_bank_branch_id',
            'address:ntext',
            'bank_manager',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {

                    $updateBtn = Yii::$app->user->can('update_ro_general_journal') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
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