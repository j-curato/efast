<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OfficeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Offices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="office-index">


    <p>
        <?= Html::a('Create Office', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary'
        ],
        'columns' => [

            'office_name',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ]
        ],
    ]); ?>


</div>
<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    ['depends' => [JqueryAsset::class]]
)
?>