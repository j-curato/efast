<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentTracker */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Document Trackers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="document-tracker-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<div class="container">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'date_recieved',
                'value'=>function($model){
                    return date('F d, Y',strtotime($model->date_recieved));
                }
            ],
            'document_type',
            'status',
            'document_number',
            [
                'attribute'=>'document_date',
                'value'=>function($model){
                    return date('F d,Y',strtotime($model->document_date));
                }

            ],
            [
                'label'=>'Responsible Office',
                'value'=>function($model){
                    $office = '';
                    foreach($model->documentTrackerOffice as $val){

                        if ($office !==''){
                            $office .= ', ';
                        }
                        $office .=  $val->office;

                    }
                    return $office;
                }

            ],
            'details:ntext',
        ],
    ]) ?>

    <table class="">
        <thead>
            <th>
                Document Links
            </th>
        </thead>
        <tbody>
            <?php
            foreach ($model->documentTrackerLinks as $val) {
                echo "<tr>
                <td><a href='{$val->link}' target='_blank'>{$val->link}</a></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
    <table class="">
        <thead>
            <th>
                Document Compliance Links
            </th>
        </thead>
        <tbody>
            <?php
            foreach ($model->documentTrackerComplinceLinks as $val) {
                echo "<tr>
                <td><a href='{$val->link}' target='_blank'>{$val->link}</a></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

</div>
</div>

<style>
    table,
    th,
    td {
        padding: 15px;

    }
    table{
        width: 100%;
    }
    .container{
        background-color: white;
    }
</style>