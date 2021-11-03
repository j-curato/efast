 <?php

    use app\models\Books;
    use app\models\DvAucs;
    use app\models\DvAucsSearch;
    use aryelds\sweetalert\SweetAlert;
    use aryelds\sweetalert\SweetAlertAsset;
    use kartik\date\DatePicker;
    use kartik\grid\GridView;
    use kartik\select2\Select2;
    use kartik\time\TimePicker;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model app\models\CashDisbursement */
    /* @var $form yii\widgets\ActiveForm */
    ?>

 <div class="cash-disbursement-form">


     <?php
        $dv_aucs_id = !empty($model->dv_aucs_id) ? $model->dv_aucs_id : "";

        $is_cancelled = $model->is_cancelled;
        ?>
     <div class="container">


         <div class="row">
             <div class="col-sm-12" style="text-align:center;color:red">
                 <h4 id="link">
                 </h4>
             </div>
         </div>
         <form id="cash_disbursement_form">

             <div class="row">
                 <?= Html::input('text', 'update_id', !empty($model->id) ? $model->id : '', [
                        "style" => "display:none"
                    ]) ?>

                 <div class="col-sm-3">
                     <label for="reporting_period"> Reporting Period</label>
                     <?php
                        echo DatePicker::widget([
                            'name' => 'reporting_period',
                            'value' => !empty($model->reporting_period) ? $model->reporting_period : '',
                            'options' => [
                                'required' => true
                            ],
                            'pluginOptions' => [
                                'format' => "yyyy-mm",
                                'autoclose' => true,
                                'startView' => "year",
                                'minViewMode' => 'months'

                            ]
                        ])

                        ?>
                 </div>
                 <div class="col-sm-3">
                     <label for="issuance_date"> Issuance Date</label>
                     <?php
                        echo DatePicker::widget([
                            'name' => 'issuance_date',
                            'value' => !empty($model->issuance_date) ? $model->issuance_date : '',
                            'pluginOptions' => [
                                'format' => "yyyy-mm-dd",
                                'autoclose' => true,

                            ]
                        ])

                        ?>
                 </div>
                 <div class="col-sm-3">
                     <label for="book">Books</label>
                     <?php

                        echo Select2::widget([
                            'name' => "book",
                            'value' => !empty($model->book_id) ? $model->book_id : '',
                            'data' => ArrayHelper::map(Books::find()->asArray()->all(), "id", "name"),
                            'options' => [
                                'placeholder' => "Select Book"
                            ]
                        ])
                        ?>
                 </div>
                 <div class="col-sm-3">
                     <label for="mode_of_payment">Mode of Payment</label>
                     <?php

                        echo Select2::widget([
                            'name' => "mode_of_payment",
                            'value' => !empty($model->mode_of_payment) ? strtolower($model->mode_of_payment) : '',
                            'data' => ['lbp check' => "LBP Check", 'ada' => "ADA", 'echeck' => "eCheck"],
                            "options" => [
                                "placeholder" => "Select Mode of Payment"
                            ]
                        ])
                        ?>
                 </div>

             </div>
             <div class="row" style="margin-bottom: 20px;">
                 <div class="col-sm-3">
                     <label for="check_ada_no">Check Number</label>
                     <?php
                        // echo "

                        // <input type='text' class='form-control' name='check_ada_no' value='".!empty($model->check_or_ada_number)?$model->check_or_ada_no:''."'>
                        // ";
                        ?>
                     <?= Html::input('text', 'check_ada_no', !empty($model->check_or_ada_no) ? $model->check_or_ada_no : '', ['class' => 'form-control', 'required' => true]) ?>
                 </div>
                 <div class="col-sm-3">
                     <label for="check_ada_no">ADA Number</label>
                     <?php
                        // echo "

                        // <input type='text' class='form-control' name='check_ada_no' value='".!empty($model->check_or_ada_number)?$model->check_or_ada_no:''."'>
                        // ";
                        ?>
                     <?= Html::input('text', 'ada_number', !empty($model->ada_number) ? $model->ada_number : '', ['class' => 'form-control']) ?>
                 </div>

                 <div class="col-sm-3">
                     <label for="good_cancelled"> Good/Cancelled</label>
                     <?php
                        echo Select2::widget([
                            'data' => [0 => "Good", 1 => "Cancelled"],
                            'value' => $is_cancelled,
                            "name" => "good_cancelled",
                            "options" => [
                                "autoclose" => true,
                                "placeholder" => "Good/Cancelled"
                            ]

                        ])
                        ?>
                 </div>


             </div>
             <div class="row" style="margin-bottom: 20px;">

                 <div class="col-sm-3">
                     <label for="begin_time">Begin Time</label>
                     <?php
                        date_default_timezone_set('Asia/Manila');
                        $begin_time = date('h:i A');
                        $end_time = date('h:i A');

                        if (!empty($model)) {
                            $begin_time = date('h:i A', strtotime($model->begin_time));
                            $end_time = date('h:i A', strtotime($model->out_time));
                        }
                        echo TimePicker::widget([
                            'name' => 'begin_time',
                            'value' => $begin_time
                        ]);

                        ?>
                 </div>
                 <div class="col-sm-3">
                     <label for="out_time">Out Time</label>
                     <?php
                        echo TimePicker::widget([
                            'name' => 'out_time',
                            'value' => $end_time
                        ]);

                        ?>
                 </div>
             </div>




             <!-- <div class="col-sm-3" style="height:60x">
            <label for="book">Book</label>
            <select id="book" name="book" class="book select" style="width: 100%; margin-top:50px" required>
                <option></option>
            </select>
        </div> -->
             <?php
                $searchModel = new DvAucsSearch();
                $searchModel->id = $dv_aucs_id;
                $searchModel->is_cancelled = 0;

                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $dataProvider->sort = ['defaultOrder' => ['id' => 'DESC']];

                $qwe = DvAucs::find()->select(['id'])->all();
                $x = [];;
                foreach ($qwe as $v) {
                    $x[] = $v->id;
                }
                // ob_clean();
                // echo "<pre>";
                // var_dump($qwe);
                // echo "</pre>";
                // return ob_get_clean();
                ?>
             <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,

                    'panel' => [
                        'type' => GridView::TYPE_PRIMARY,
                        'heading' => "List Of DV's",
                    ],

                    'toggleDataOptions' => ['maxCount' => 100],
                    'pjax' => true,
                    'export' => false,
                    'floatHeaderOptions' => [
                        'top' => 50,
                        'position' => 'absolute',
                    ],

                    'columns' => [

                        'id',

                        // [
                        //     "label" => "id",
                        //     "attribute" => "id",
                        //     // "filter" => function () use ($dv_aucs_entries_id) {
                        //     //     return $dv_aucs_entries_id;
                        //     // }
                        // ],

                        [

                            'class' => '\kartik\grid\CheckboxColumn',
                            'checkboxOptions' => function ($model, $key, $index, $column) use ($x, $dv_aucs_id) {
                                // return ['value' => $model->id,  'style' => 'width:20px;', 'class' => 'checkbox'];
                                $bool = in_array($dv_aucs_id, $x);
                                if ($dv_aucs_id === $model->id) {
                                    return ['checked' => $bool];
                                }
                            }
                        ],
                        [
                            'label' => 'DV Number',
                            'attribute' => 'dv_number',
                            // 'value' => "dv_number"
                            // 'filter' => Html::activeDropDownList(
                            //     $searchModel,
                            //     'recordAllotment.fund_cluster_code_id',
                            //     ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
                            //     ['class' => 'form-control', 'prompt' => 'Major Accounts']
                            // )

                        ],
                        [
                            'label' => 'Amount',
                            // 'attribute' => 'amount_disbursed',
                            // 'filter' => false,
                            'format' => ['decimal', 2],
                            'value' => function ($model) {
                                $query = (new \yii\db\Query())
                                    ->select(["SUM(dv_aucs_entries.amount_disbursed) as total_disbursed"])
                                    ->from('dv_aucs')
                                    ->join("LEFT JOIN", "dv_aucs_entries", "dv_aucs.id = dv_aucs_entries.dv_aucs_id")
                                    ->where("dv_aucs.id =:id", ['id' => $model->id])
                                    ->one();

                                return $query['total_disbursed'];
                            }
                        ],
                        [
                            'label' => 'Payee',
                            'attribute' => 'payee.account_name'
                        ],
                        [
                            'label' => 'Particular',
                            'attribute' => 'particular',

                        ],

                    ],
                ]); ?>
             <button type="button" name="" id="submit" class="btn btn-success" style="width: 100%;">Save</button>
         </form>

     </div>


     <style>
         .container {
             background-color: white;
             padding: 12px
         }

         .grid-view td {
             white-space: normal;
             width: 5rem;
             padding: 0;
         }
     </style>
 </div>

 <?php
    SweetAlertAsset::register($this);

    $script = <<< JS
    var cash_link=undefined;
    var bbb=undefined;
    $(document).ready(function(){

        // $.getJSON(window.location.pathname +" ?r=")

     
    })
    $("#submit").click(function(e){
            e.preventDefault();
            
            $.ajax({
                type:"POST",
                url:window.location.pathname + "?r=cash-disbursement/insert-cash-disbursement",
                data:$('#cash_disbursement_form').serialize(),
                success:function(data){
                    // console.log(JSON.parse(data))
                    var res = JSON.parse(data)
                    console.log(res.error)

                    if (res.isSuccess==true){
                        swal({
                            title:"Success",
                            type:'success',
                            button:false,
                            timer:3000,
                        },function(){
                            window.location.href = window.location.pathname +"?r=cash-disbursement/view&id="+res.id
                        })
                    }
                    else if (res.isSuccess ==false){
                        var q='';
                        for(var k in res.error) {
                            console.log(k, res.error[k][0]);
                            q+=res.error[k][0] + ' ,';
                            }  
                        swal({
                            title:"Error",
                            text:q,
                            type:'error',
                            button:false,
                            timer:3000,
                        })
                    }
                    else if (res.isSuccess == 'exist'){
                        console.log(res.id)

                        $('#link').text('This DV Na disbursed na ')
                        cash_link = window.location.pathname +"?r=cash-disbursement/view&id="+res.id
                    
                     bbb = $(`<a type="button" href='`+ cash_link+`' >link here</a>`);
                                 bbb.appendTo($("#link"));
                        swal({
                            title:"Error",
                            text:"DV na disbursed na",
                            type:'error',
                            button:false,
                            timer:3000,
                           
                        })
                    }
                }
            })
        })
        

JS;
$this->registerJs($script);
?>