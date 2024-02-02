<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PreRepairInspectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pre Repair Inspections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pre-repair-inspection-index">


    <p>
        <?= Yii::$app->user->can('create_pre_repair_inspection') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>


    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Pre-Repair Inspection'
        ],
        'columns' => [

            'serial_number',
            'date',
            'findings:ntext',
            'recommendation:ntext',

            [
                'attribute' =>    'fk_requested_by',
                'value' => function ($model) {
                    $f_name =  $model->requestedBy->f_name ?? '';
                    $m_name =  $model->requestedBy->m_name[0] ?? '';
                    $l_name =  $model->requestedBy->l_name ?? '';



                    return "$f_name $m_name. $l_name ";
                }
            ],
            [
                'attribute' =>    'fk_accountable_person',
                'value' => function ($model) {
                    $f_name =  $model->accountablePerson->f_name ?? '';
                    $m_name =  $model->accountablePerson->m_name[0] ?? '';
                    $l_name =  $model->accountablePerson->l_name ?? '';



                    return "$f_name $m_name. $l_name ";
                }
            ],

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_pre_repair_inspection') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>
<?php

$script = <<<JS
            var i=false;
        $('#mdModal').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

        
  
JS;
$this->registerJs($script);
?>