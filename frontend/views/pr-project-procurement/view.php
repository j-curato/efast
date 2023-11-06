<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrProjectProcurement */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Project Procurements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-project-procurement-view">
    <p>
        <?= Html::button('Update', [
            'value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=pr-project-procurement/update&id=' . $model->id),
            'id' => 'mdModal', 'class' => 'btn btn-primary', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
        ]); ?>
    </p>

    <div class="panel panel-danger">
        <div class="panel-heading"><?= $this->title ?></div>
        <div class="panel-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'title:ntext',
                    'amount',
                    [
                        'label' => 'Office',
                        'attribute' => 'pr_office_id',
                        'value' => function ($model) {
                            return $model->office->office . ' ' . $model->office->division . ' ' . $model->office->unit;
                        }
                    ],
                    [
                        'label' => 'Employee',
                        'attribute' => 'employee_id',
                        'value' => function ($model) {
                            $name = $model->employee->f_name . ' ' . $model->employee->m_name[0] . '. ' . $model->employee->l_name;
                            return strtoupper($name);
                        }
                    ],
                    'amount',

                ],
            ]) ?>
        </div>
    </div>


</div>

<?php
$script = <<<JS
    

    
         $('#mdModal').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });

JS;
$this->registerJs($script);
?>