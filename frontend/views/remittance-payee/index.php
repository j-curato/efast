<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;
use app\components\helpers\MyHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RemittancePayeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Remittance Payees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remittance-payee-index">


    <p>
        <?= Html::a('<i class="fa fa-pencil-alt"></i> Create ', ['create'], ['class' => 'btn btn-success modalButtonCreate'])  ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Remittance Payees'
        ],
        'columns' => [

            // 'id',
            [
                'label' => 'Payee',
                'attribute' => 'payee_id',
                'value' => 'payee.account_name'
            ],
            [
                'label' => 'General Ledger',
                'attribute' => 'object_code',
                'value' => function ($model) {

                    return $model->generalLedger->uacs . '-' . $model->generalLedger->general_ledger;
                }
            ],

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
<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    [
        'depends' => [JqueryAsset::class]
    ]
);

?>