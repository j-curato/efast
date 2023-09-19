<?php

use app\models\ProcessOrs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProccessOrsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Process Ors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Process Ors', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <!-- ANG MODEL ANI KAY SA PROCESS ORS ENTRIES -->
    <?php
    $processOrs=ProcessOrs::find();
    ?>
    <table class="table">
        <tbody>
            <?php
            // foreach($processOrs->processOrsEntries as $value){
            //     echo "<tr>
            //         <td>{$value->chartOfAccount->general_ledger}</td>
            //         <td>{$value->amount}</td>
            //     </tr>";
            // }
            
            ?>
        </tbody>
    </table>
</div>