<?php

use app\models\FundClusterCode;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RecordAllotmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Record Allotments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-allotments-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Record Allotments', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'Document Recieve',
                'attribute' => 'documentRecieve.name'
            ],
            [
                'label' => 'Fund CLuster Code',
                'attribute' => 'fundClusterCode.name',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'fund_cluster_code_id',
                    ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
                    ['class' => 'form-control', 'prompt' => 'Fund Cluster Codes']
                )
            ],

            [
                'label' => 'Financing Source Code',
                'attribute' => 'financingSourceCode.name',
            ],
            [
                'label' => 'Fund Category and Classification Code',
                'attribute' => 'fundCategoryAndClassificationCode.name'
            ], [
                'label' => 'Authorization Code',
                'attribute' => 'authorizationCode.name'
            ],
            [
                'label' => 'MFO/PAP Code',
                'attribute' => 'mfoPapCode.code'
            ],
            [
                'label' => 'MFO/PAP Name',
                'attribute' => 'mfoPapCode.name'
            ],
            [
                'label' => 'Fund Source',
                'attribute' => 'fundSource.name'
            ],
            'reporting_period',
            'serial_number',
            'allotment_number',
            'date_issued',
            'valid_until',
            'particulars',

            ['class' => 'yii\grid\ActionColumn'],

        ],
    ]); ?>


</div>