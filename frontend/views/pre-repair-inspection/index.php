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
        <?= Html::a('<i class="fa fa-pencil-alt"></i> Create', ['create'], ['class' => 'btn btn-success lrgModal']); ?>

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
                    return MyHelper::gridDefaultAction($model->id, 'lrgModal');
                }
            ],
        ],
    ]); ?>


</div>
<?php

$script = <<<JS
            var i=false;
        $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

        
  
JS;
$this->registerJs($script);
?>