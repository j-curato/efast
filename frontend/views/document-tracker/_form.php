<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2 as Select2Select2;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\DocumentTracker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-tracker-form">
    <div class="container">
        <form id='documentTrackerForm'>
            <?php
            date_default_timezone_set('Asia/Manila');
            $date_recieve = empty($model->date_recieved) ? date('F d, y') : date('F d,Y', strtotime($model->date_recieved));
            $status = '';
            $document_date =  empty($model->document_date) ? date('F d, y') : date('F d, Y', strtotime($model->document_date));
            $links = [];
            $document_type = '';
            $document_number = '';
            $details = '';
            $compliance_links = [];
            $model_office = [];
            $update_id = null;
            if (!empty($model)) {
                // $date_recieve = date('F d,Y', strtotime($model->date_recieved));
                $update_id = $model->id;
                $document_type = $model->document_type;
                $status = $model->status;
                // $document_date = date('F d, Y', strtotime($model->document_date));
                $document_number  = $model->document_number;
                $details = $model->details;
                foreach ($model->documentTrackerLinks as $x) {
                    $links[] = $x->link;
                }
                foreach ($model->documentTrackerComplinceLinks as $x) {
                    $compliance_links[] = $x->link;
                }
                foreach ($model->documentTrackerOffice as $x) {
                    $model_office[] = $x->office;
                }
            }
            echo "<input type='hidden' name='update_id' value='$update_id'>";
            ?>
            <div class="row">

                <div class="col-sm-3">
                    <label for="date_recieved"> Date Recieve</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'date_recieved',
                        'value' => $date_recieve,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'MM dd, yyyy'
                        ]
                    ])

                    ?>

                </div>
                <div class="col-sm-3">
                    <label for="document_type">Document Type</label>
                    <?php
                    echo Select2::widget([
                        'name' => 'document_type',
                        'data' => [
                            'Accounting Memo'=>'Accounting Memo',
                            'COA AOM`s'=>'COA AOM`s',
                            'COA NS'=>'COA NS',
                            'COA NSSDC'=>'COA NSSDC',
                            'COA ND'=>'COA ND',
                            'FAD Memo'=>'FAD Memo',
                            'Reports'=>'Reports',
                            'Other Files'=>'Other Files',
                        ],
                        'value' => $document_type,
                        'pluginOptions' => [
                            'placeholder' => 'Select Document Type',
                            'autoclose' => true
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="status">Status</label>
                    <?php
                    echo Select2::widget([
                        'data' => [

                            'For Information'=>'For Information',
                            'Responded'=>'Responded',
                            'No Response Yet'=>'No Response Yet',
                            'Responded But Partially Complied'=>'Responded But Partially Complied',
                            'Responded But No Compliance Yet'=>'Responded But No Compliance Yet',
                            'Fully Compiled'=>'Fully Compiled',

                        ],
                        'name' => 'status',
                        'value' => $status,
                        'pluginOptions' => [
                            'placeholder' => 'Select status',
                            'autoclose' => true
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-3">

                    <label for="document_date">Document Date</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'document_date',
                        'value' => $document_date,
                        'pluginOptions' => [
                            'format' => 'MM dd, yyyy',
                            'autoclose' => true
                        ]
                    ])
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <label for="office">Responsible Office</label>
                    <?php
                    $office = [
                        'All' => 'All',
                        'RO' => 'RO',
                        'PSO ADN' => 'PSO ADN',
                        'PSO ADS' => 'PSO ADS',
                        'PSO PDI' => 'PSO PDI',
                        'PSO SDN' => 'PSO SDN',
                        'PSO SDS' => 'PSO SDS',
                    ];
                    echo Select2::widget([
                        'data' => $office,
                        'name' => 'office',
                        'value'=>$model_office,

                        'pluginOptions' => [
                            'placeholder' => 'Select Responsible Office',
                            'autoclose' => true,
                            'multiple' => true
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="document_number"> Document Number</label>
                    <input type="text" name="document_number" value= '<?php echo $document_number;?>'class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="details">Details</label>
                    <textarea name="details" id="details" style="width: 100%; max-width:100%" rows="3">
                    <?php echo trim($details) ?>
                </textarea>
                </div>
            </div>
            <table id="link_table" class="table" style="width: 100%;">
                <tbody>
                    <?php
                    function linkRow($data)
                    {
                        echo "<tr>
                        <td>

                            <label for='link'>Link</label>
                            <input type='text' class='form-control'  value='$data' name='link[]'>
                        </td>
                        <td class='action' align='center'>
                            <div class='link-btn'> 
                            
                            <button  type='button' class='btn-xs btn-danger link-remove' onClick='removeLink(this)'><i class='fa fa-times'></i></button>
                            <button type='button' class='btn-xs btn-success link-add' onClick='addLink()'><i class='fa fa-pencil-alt'></i></button>
                            </div>
                        </td>
                    </tr>";
                    }
                    if (!empty($links)) {
                        foreach ($links as $v) {
                            linkRow($v);
                        }
                    } else {
                        linkRow('');
                    }


                    ?>
                    <!-- <tr>
                        <td>

                            <label for="link">Link</label>
                            <input type="url" class="form-control" name="link[]">
                        </td>
                        <td>
                            <button type='button' class="btn btn-danger link-remove" onClick='removeLink(this)'>remove</button>
                            <button type='button' class="btn btn-success link-add" onClick='addLink()'>add</button>
                        </td>
                    </tr> -->
                </tbody>
            </table>
            <table id="compliance_table" class="table">
                <tbody>

                    <?php
                    function complianceLink($data)
                    {
                        echo " <tr>
                                <td>
                                    <label for='compliance'>Compliance Link</label>
                                    <input type='text' class='form-control' value='$data' name='compliance[]'>
                                </td>
                                <td  class='action'>
                                    <button type='button' class='btn-xs btn-danger compliance-remove'  onclick='removeCompliance(this)'><i class='fa fa-times'></i></button>

                                    <button type='button' class='btn-xs btn-success compliance-add' onclick='addCompliance()'><i class='fa fa-pencil-alt'></i></button>
                                </td>
                            </tr>";
                    }
                    if (!empty($compliance_links)) {
                        foreach ($compliance_links as $val) {
                            complianceLink($val);
                        }
                    } else {
                        complianceLink('');
                    }


                    ?>

                </tbody>
            </table>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>
<style>
    .container {
        background-color: white;
        padding: 30px;
    }

    .action {
        width: 100px;
        text-align: right;

    }
</style>
<script>
    function addLink() {
        var row = `
        <tr>
                <td>
                    <label for="link">Link</label>
                    <input type="text" class="form-control" name="link[]">
                </td>
                <td  class='action'>
                    <button type='button' class="btn-xs btn-danger  link-remove" onClick='removeLink(this)'><i class='fa fa-times'></i></button>
                    <button type='button' class="btn-xs btn-success link-add" onClick='addLink()'><i class='fa fa-pencil-alt'></i></button>
                </td>
            </tr>
        `
        $('#link_table > tbody').append(row)
    }

    function removeLink(q) {
        q.closest('tr').remove()
    }

    function addCompliance() {
        var row = `
        <tr>
                <td>
                    <label for="comliance">Compliance Link</label>
                    <input type="text" class="form-control" name="compliance[]">
                </td>
                <td  class='action'>
                    <button class="btn-xs btn-danger remove" onClick='removeCompliance(this)'><i class='fa fa-times'></i></button>
                    <button class="btn-xs btn-success compliance-add" onClick='addCompliance()'><i class='fa fa-pencil-alt'></i></button>
                </td>
            </tr>
        `
        $('#compliance_table > tbody').append(row)
    }

    function removeCompliance(q) {
        q.closest('tr').remove()
    }
</script>

<?php
SweetAlertAsset::register($this);
$script = <<<JS
    var tracker = $('#documentTrackerForm')
   tracker.submit((e)=>{
       e.preventDefault();
       $.ajax({
           type:'POST',
           url:window.location.pathname +'?r=document-tracker/insert',
           data:tracker.serialize(),
           success:function(data){
               console.log(data)
               var res = JSON.parse(data)
               if (res.success){
                swal({
                    type:'success',
                    button:false,
                    title:'Successfuly Saved',
                    timer:3000
                },function(){
                    window.location.href = window.location.pathname +'?r=document-tracker/view&id='+res.id
                })
               }else{
                   swal({
                       type:'error',
                       button:false,
                       title:res.error,
                       timer:4000
                   })
               }
           }
       })
       
    })

JS;
$this->registerJs($script);
?>