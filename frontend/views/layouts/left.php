<?php

use yii\helpers\Html;
use dmstr\widgets\Menu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\FileHelper;

$user_data = Yii::$app->memem->getUserData();

?>

<aside class="main-sidebar">

    <style>
        .button {
            position: absolute;
            bottom: 40px;
            right: 40px;
            /* Adjust the distance from the bottom as needed */
            /* Adjust the distance from the left as needed */
            padding: 10px 15px;

            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            background-color: #787272;
        }
    </style>

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel" style="margin-bottom: 20px;height:18rem;">
            <div>

                <div class=" " style="text-align: center;padding:0;width:100%">
                    <!-- <div class=" image"> -->
                    <!-- <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image" /> -->
                    <?php
                    $imagePath = $directoryAsset . "/img/user2-160x160.jpg";
                    $filePath = '@webroot/profile_pics';
                    $usr_id = Yii::$app->user->identity->id;
                    $realFilePathPng = Yii::getAlias($filePath . "/$usr_id.png");
                    $realFilePathJpg = Yii::getAlias($filePath . "/$usr_id.jpg");
                    if (file_exists($realFilePathPng) && is_file($realFilePathPng)) {
                        // File exists in the folder
                        $imagePath =  Yii::$app->request->baseUrl . '/profile_pics' . "/$usr_id.png";
                    } else if (file_exists($realFilePathJpg) && is_file($realFilePathJpg)) {
                        $imagePath =  Yii::$app->request->baseUrl . '/profile_pics' . "/$usr_id.jpg";
                    }
                    // Display the image using Html::img
                    echo Html::img($imagePath, ['alt' => 'Image Example', 'style' => 'width:120px;height:120px; margin:0;padding:0;']);
                    ?>
                    <div style="color:white">


                        <div style="background-color: black;">
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['site/upload-image',], ['class' => '  uploadImage button',]); ?>
                        </div>
                    </div>
                    <div>
                    </div>
                </div>

                <!-- <div class="info"> -->
                <div class="" style="text-align: center; color:white;padding-top:10px;padding-left:5px;white-space: normal;">
                    <span>
                        <?php
                        if (!empty($user_data->employee->employee_id)) {
                            $name = $user_data->employee->f_name . ' ' . $user_data->employee->m_name[0] . '. ' . $user_data->employee->l_name;

                            $name .= !empty($user_data->employee->suffix) ? ', ' . $user_data->employee->suffix : '';
                            echo strtoupper($name);
                        }
                        ?>
                </div>
                </span>
            </div>
        </div>
        <!-- ?= $directoryAsset ?>/img/user2-160x160.jpg -->

        <!-- search form -->
        <!-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..." />
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form> -->
        <!-- /.search form -->
        <style>
            .shit {
                color: red;
            }
        </style>
        <?php

        $po_modules = [
            'options' => ['class' => 'sidebar-menu tree text-truncate', 'data-widget' => 'tree', 'style' => 'width: inherit'],
            'items' => [
                Yii::$app->user->can('po_master_records') ?   [
                    'label' => 'Master Records',
                    'url' => '#',
                    'items' => [
                        Yii::$app->user->can('supper-user') ?  ['label' => 'Bank Account', 'icon' => 'circle-o', 'url' => ['/bank-account'],] : [],
                        Yii::$app->user->can('po_check_range') ?  ['label' => 'Check Range', 'icon' => 'circle-o', 'url' => ['/check-range'],] : [],
                        Yii::$app->user->can('po_asignatory') ?     ['label' => 'PO Asignatory', 'icon' => 'circle-o', 'url' => ['/po-assignatory'],] : [],
                        Yii::$app->user->can('po_responsibility_center') ?     ['label' => 'PO Responsibility Center', 'icon' => 'circle-o', 'url' => ['/po-responsibility-center'],] : [],
                        Yii::$app->user->can('super-user') ?     ['label' => 'Payee', 'icon' => 'circle-o', 'url' => ['/payee'],] : [],
                    ],
                ] : [],
                Yii::$app->user->can('po_accounting') ?   [
                    'label' => 'Accounting',
                    'url' => '#',
                    'items' => [
                        [
                            'label' => 'Transaction',
                            'icon' => 'circle-o',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('po_advances') ?     ['label' => 'Advances', 'icon' => 'circle-o', 'url' => ['/advances'],] : [],
                                Yii::$app->user->can('po_transaction') ?     ['label' => 'PO Transaction', 'icon' => 'circle-o', 'url' => ['/po-transaction'],] : [],
                                Yii::$app->user->can('liquidation') ?     ['label' => 'Liquidation', 'icon' => 'circle-o', 'url' => ['/liquidation'],] : [],
                                Yii::$app->user->can('liquidation') ?     ['label' => 'Cancelled Check', 'icon' => 'circle-o', 'url' => ['/liquidation/cancelled-check-index'],] : [],
                                Yii::$app->user->can('po_transmittal') ? ['label' => 'DV Transmittal', 'icon' => 'circle-o', 'url' => ['/po-transmittal'],] : [],

                            ],
                        ],
                        [
                            'label' => 'Reports',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('po_alphalist') ?     ['label' => 'Alphalist', 'icon' => 'circle-o', 'url' => ['/alphalist'],] : [],
                                Yii::$app->user->can('po_cibr') ?     ['label' => 'CIBR', 'icon' => 'circle-o', 'url' => ['/cibr'],] : [],
                                Yii::$app->user->can('po_cdr') ?     ['label' => 'CDR', 'icon' => 'circle-o', 'url' => ['/cdr'],] : [],
                                Yii::$app->user->can('po_fur') ?     ['label' => 'FUR', 'icon' => 'circle-o', 'url' => ['/fur'],] : [],
                                Yii::$app->user->can('po_rod') ?     ['label' => 'ROD', 'icon' => 'circle-o', 'url' => ['/rod'],] : [],
                                // Yii::$app->user->can('po_transmittal') ? ['label' => 'Returned DV`s', 'icon' => 'circle-o', 'url' => ['/po-transmittal/returned-liquidation'],] : [],
                                Yii::$app->user->can('advances_liquidation') ?     ['label' => 'Advances/Liquidation', 'icon' => 'circle-o', 'url' => ['/report/advances-liquidation'],] : [],

                            ],
                        ],
                        [
                            'label' => 'Querys',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('po_fnd_src_fur') ?     ['label' => 'Fund Source FUR', 'icon' => 'circle-o', 'url' => ['/report/fund-source-fur'],] : [],
                                Yii::$app->user->can('po_smry_fnd_src_fur') ?     ['label' => 'Summary Fund Source FUR', 'icon' => 'circle-o', 'url' => ['/report/summary-fund-source-fur'],] : [],
                                Yii::$app->user->can('po_smry_bgt_fur') ?     ['label' => 'Summary Budget Year FUR', 'icon' => 'circle-o', 'url' => ['/report/budget-year-fur'],] : [],
                                Yii::$app->user->can('po_adqcy_rsrc') ?     ['label' => 'Adequacy of Resource', 'icon' => 'circle-o', 'url' => ['/report/province-adequacy'],] : [],
                                // Yii::$app->user->can('po_accounting_admin') ?     ['label' => 'MLP', 'icon' => 'circle-o', 'url' => ['/monthly-liquidation-program'],] : [],

                            ],
                        ]

                    ],
                ] : [],
                Yii::$app->user->can('property_modules') ?    [
                    'label' => 'Property',
                    'url' => '#',
                    'items' => [

                        [
                            'label' => 'Transaction',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('property') ?     ['label' => 'Property', 'icon' => 'circle-o', 'url' => ['/property'],] : [],
                                Yii::$app->user->can('ptr') ?     ['label' => 'PTR', 'icon' => 'circle-o', 'url' => ['/ptr'],] : [],
                                Yii::$app->user->can('par') ?     ['label' => 'PAR', 'icon' => 'circle-o', 'url' => ['/par'],] : [],
                                Yii::$app->user->can('property_card') ?     ['label' => 'Property Card', 'icon' => 'circle-o', 'url' => ['/property-card'],] : [],
                                Yii::$app->user->can('rlsddp') ?     ['label' => 'RLSDDP', 'icon' => 'circle-o', 'url' => ['/rlsddp'],] : [],
                                Yii::$app->user->can('iirup') ?     ['label' => 'IIRUP', 'icon' => 'circle-o', 'url' => ['/iirup'],] : [],
                            ],
                        ],
                        [
                            'label' => 'Reports',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('property') ?  ['label' => 'Property Database', 'icon' => 'circle-o', 'url' => ['/property/property-database'],] : [],
                                Yii::$app->user->can('rpcppe') ?     ['label' => 'RPCPPE', 'icon' => 'circle-o', 'url' => ['/rpcppe'],] : [],
                                Yii::$app->user->can('property') ?     ['label' => 'PPELC', 'icon' => 'circle-o', 'url' => ['/report/ppelc'],] : [],
                                Yii::$app->user->can('property') ?     ['label' => 'Accountabilities', 'icon' => 'circle-o', 'url' => ['/report/user-properties'],] : [],
                            ],
                        ],
                    ],
                ] : [],
                Yii::$app->user->can('po-reports') ?  [
                    'label' => 'Reports',
                    'url' => '#',
                    'items' => [
                        Yii::$app->user->can('province') ?     ['label' => 'Alphalist', 'icon' => 'circle-o', 'url' => ['/alphalist'],] : [],
                        Yii::$app->user->can('po_cibr') ?     ['label' => 'CIBR', 'icon' => 'circle-o', 'url' => ['/cibr'],] : [],
                        Yii::$app->user->can('po_cdr') ?     ['label' => 'CDR', 'icon' => 'circle-o', 'url' => ['/cdr'],] : [],
                        Yii::$app->user->can('province') ?     ['label' => 'FUR', 'icon' => 'circle-o', 'url' => ['/fur'],] : [],
                        Yii::$app->user->can('rod') ?     ['label' => 'ROD', 'icon' => 'circle-o', 'url' => ['/rod'],] : [],
                        Yii::$app->user->can('po_transmittal') ? ['label' => 'Returned DV`s', 'icon' => 'circle-o', 'url' => ['/po-transmittal/returned-liquidation'],] : [],
                        Yii::$app->user->can('advances_liquidation') ?     ['label' => 'Advances/Liquidation', 'icon' => 'circle-o', 'url' => ['/report/advances-liquidation'],] : [],

                    ],
                ] : [],
                Yii::$app->user->can('po-query') ?  [
                    'label' => 'Query',
                    'url' => '#',
                    'items' => [
                        Yii::$app->user->can('province') ?     ['label' => 'Fund Source FUR', 'icon' => 'circle-o', 'url' => ['/report/fund-source-fur'],] : [],
                        Yii::$app->user->can('summary_fund_source_fur') ?     ['label' => 'Summary Fund Source FUR', 'icon' => 'circle-o', 'url' => ['/report/summary-fund-source-fur'],] : [],
                        Yii::$app->user->can('summary_fund_source_fur') ?     ['label' => 'Summary Budget Year FUR', 'icon' => 'circle-o', 'url' => ['/report/budget-year-fur'],] : [],
                        Yii::$app->user->can('province') ?     ['label' => 'ROD', 'icon' => 'circle-o', 'url' => ['/rod'],] : [],
                        Yii::$app->user->can('province') ?     ['label' => 'Adequacy of Resource', 'icon' => 'circle-o', 'url' => ['/report/province-adequacy'],] : [],

                    ],
                ] : [],
                Yii::$app->user->can('procurement') ?    [
                    'label' => 'Procurement',
                    'url' => '#',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('po_procurement_admin') ?     ['label' => 'RBAC', 'icon' => 'circle-o', 'url' => ['/bac-composition'],] : [],

                            ],
                        ],
                        [
                            'label' => 'Transaction',
                            'icon' => 'circle-o',
                            'url' => '#',
                            'items' => [
                                // Yii::$app->user->can('super-user') ?     ['label' => 'Activity/Project Procurement', 'icon' => 'circle-o', 'url' => ['/pr-project-procurement'],] : [],
                                Yii::$app->user->can('ppmp') ?     ['label' => 'Supplemental PPMP', 'icon' => 'circle-o', 'url' => ['/supplemental-ppmp'],] : [],
                                Yii::$app->user->can('purchase_request') ?     ['label' => 'Purchase Request', 'icon' => 'circle-o', 'url' => ['/pr-purchase-request'],] : [],
                                Yii::$app->user->can('rfq') ?     ['label' => 'RFQ', 'icon' => 'circle-o', 'url' => ['/pr-rfq'],] : [],
                                Yii::$app->user->can('aoq') ?     ['label' => 'AOQ', 'icon' => 'circle-o', 'url' => ['/pr-aoq'],] : [],
                                Yii::$app->user->can('purchase_order') ?     ['label' => 'Purchase Order', 'icon' => 'circle-o', 'url' => ['/pr-purchase-order'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Transmittal', 'icon' => 'circle-o', 'url' => ['/purchase-order-transmittal'],] : [],

                            ],
                        ],
                        [
                            'label' => 'Reports',
                            'icon' => 'circle-o',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('super-user') ?     ['label' => 'Procurement Summary', 'icon' => 'circle-o', 'url' => ['/report/procurement-summary'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'PO Summary', 'icon' => 'circle-o', 'url' => ['/report/pr-summary'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Procurement Search', 'icon' => 'circle-o', 'url' => ['/report/proc-summary'],] : [],
                            ],
                        ],




                    ],
                ] : [],

            ],



        ];

        ?>
        <?php
        $province = !empty(Yii::$app->user->identity->province) ? strtolower(Yii::$app->user->identity->province) : '';
        $division = !empty(Yii::$app->user->identity->division) ? strtolower(Yii::$app->user->identity->division) : '';
        if (strtolower($user_data->office->office_name) !== 'ro') {
            echo Menu::widget($po_modules);
        } else if (
            $province === 'ro' &&
            $division === 'idd' ||
            $division === 'sdd' ||
            $division === 'ord' ||
            $division === 'cpd' ||
            $division === 'mssu'
        ) {

            echo dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu tree text-truncate', 'data-widget' => 'tree', 'style' => 'width: inherit'],

                    'items' => [
                        [
                            'label' => 'Transaction',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('regional_transaction') ? ['label' => 'Transactions', 'icon' => 'circle-o', 'url' => ['/transaction'],] : [],

                            ],
                        ],
                        [
                            'label' => 'Reports',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('conso_dv') ?    ['label' => 'Conso Dv', 'icon' => 'circle-o', 'url' => ['/report/conso-detailed-dv'],] : [],
                                Yii::$app->user->can('fund_source_fur') ?     ['label' => 'Fund Source FUR', 'icon' => 'circle-o', 'url' => ['/report/fund-source-fur'],] : [],
                                Yii::$app->user->can('summary_fund_source_fur') ?     ['label' => 'Summary Fund Source FUR', 'icon' => 'circle-o', 'url' => ['/report/summary-fund-source-fur'],] : [],
                                Yii::$app->user->can('rod') ?     ['label' => 'ROD', 'icon' => 'circle-o', 'url' => ['/rod'],] : [],
                                Yii::$app->user->can('summary_fund_source_fur') ?     ['label' => 'Summary Budget Year FUR', 'icon' => 'circle-o', 'url' => ['/report/budget-year-fur'],] : [],
                                Yii::$app->user->can('fur-ro') ?     ['label' => 'FUR', 'icon' => 'circle-o', 'url' => ['/report/division-fur'],] : [],
                                Yii::$app->user->can('saob') ?     ['label' => 'SAOB', 'icon' => 'circle-o', 'url' => ['/report/saobs'],] : [],
                                Yii::$app->user->can('fur-mfo') ?     ['label' => 'FUR per MFO/PAP', 'icon' => 'circle-o', 'url' => ['/report/fur-mfo'],] : [],
                                Yii::$app->user->can('department-offices') ?     ['label' => 'SAOB', 'icon' => 'circle-o', 'url' => ['/saob'],] : [],


                            ],
                        ],
                        [
                            'label' => 'Procurement',
                            'url' => '#',
                            'items' => [
                                // Yii::$app->user->can('department-offices') ?     ['label' => 'Stock/Property', 'icon' => 'circle-o', 'url' => ['/pr-stock'],] : [],
                                // Yii::$app->user->can('project_procurement') ?     ['label' => 'Activity/Project Procurement', 'icon' => 'circle-o', 'url' => ['/pr-project-procurement'],] : [],
                                ['label' => 'Supplemental PPMP', 'icon' => 'circle-o', 'url' => ['/supplemental-ppmp'],],
                                Yii::$app->user->can('purchase_request') ?     ['label' => 'Purchase Request', 'icon' => 'circle-o', 'url' => ['/pr-purchase-request'],] : [],
                            ],
                        ],
                        Yii::$app->user->can('department-offices') ?    [
                            'label' => 'Inspection',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('department-offices') ?     ['label' => 'Request for Inspection', 'icon' => 'circle-o', 'url' => ['/request-for-inspection'],] : [],
                                Yii::$app->user->can('department-offices') ?     ['label' => 'Inspection Report', 'icon' => 'circle-o', 'url' => ['/inspection-report'],] : [],
                                Yii::$app->user->can('department-offices') ?     ['label' => 'IAR', 'icon' => 'circle-o', 'url' => ['/iar'],] : [],
                            ],
                        ] : [],
                        Yii::$app->user->can('department-offices') ?    [
                            'label' => 'Property',
                            'url' => '#',
                            'items' => [
                                Yii::$app->user->can('department-offices') ?     ['label' => 'RLSDDP', 'icon' => 'circle-o', 'url' => ['/rlsddp'],] : [],
                                Yii::$app->user->can('department-offices') ?     ['label' => 'IIRUP', 'icon' => 'circle-o', 'url' => ['/iirup'],] : [],
                                Yii::$app->user->can('department-offices') ?     ['label' => 'Accountabilities', 'icon' => 'circle-o', 'url' => ['/report/user-properties'],] : [],
                            ],
                        ] : [],
                        Yii::$app->user->can('super-user') ?     ['label' => 'Travel Order', 'icon' => 'circle-o', 'url' => ['/travel-order'],] : [],


                    ],



                ]
            );
        } else {
            echo dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu tree text-truncate', 'data-widget' => 'tree', 'style' => 'width: inherit'],

                    'items' => [
                        Yii::$app->user->can('accounting') ? [
                            'label' => 'Accounting',
                            'url' => '#',
                            'items' => [
                                // Yii::$app->user->can('accounting_master_records') ? 
                                [
                                    'label' => 'Master Records',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'Payor/Payee', 'icon' => 'circle-o', 'url' => ['/payee'],],
                                        ['label' => 'Chart of Accounts', 'icon' => 'circle-o', 'url' => ['/chart-of-accounts'],],
                                        ['label' => 'Major Accounts', 'icon' => 'circle-o', 'url' => ['/major-accounts'],],
                                        ['label' => 'Sub Major Accounts', 'icon' => 'circle-o', 'url' => ['/sub-major-accounts'],],
                                        ['label' => 'Sub Account 1', 'icon' => 'circle-o', 'url' => ['/sub-accounts1'],],
                                        ['label' => 'Sub Account 2', 'icon' => 'circle-o', 'url' => ['/sub-accounts2'],],
                                        ['label' => 'Books', 'icon' => 'circle-o', 'url' => ['/books'],],
                                        ['label' => 'CashFlow', 'icon' => 'circle-o', 'url' => ['/cash-flow'],],
                                        ['label' => 'Nature of Transaction', 'icon' => 'circle-o', 'url' => ['/nature-of-transaction'],],
                                        ['label' => 'MRD Classification', 'icon' => 'circle-o', 'url' => ['/mrd-classification'],],
                                        ['label' => 'Asignatory', 'icon' => 'circle-o', 'url' => ['/assignatory'],],
                                        ['label' => 'Ors Reporting Period', 'icon' => 'circle-o', 'url' => ['/ors-reporting-period'],],
                                        ['label' => 'Jev Reporting Period', 'icon' => 'circle-o', 'url' => ['/jev-reporting-period'],],
                                        ['label' => 'Fund Source Type', 'icon' => 'circle-o', 'url' => ['/fund-source-type'],],
                                        ['label' => 'Report Type', 'icon' => 'circle-o', 'url' => ['/report-type'],],
                                        ['label' => 'Divisions', 'icon' => 'circle-o', 'url' => ['/divisions'],],
                                        ['label' => 'Offices', 'icon' => 'circle-o', 'url' => ['/office'],],
                                        ['label' => 'Division/Program/Unit', 'icon' => 'circle-o', 'url' => ['/division-program-unit'],],


                                    ],
                                ],
                                // : ['label' => ''],
                                // Yii::$app->user->can('accounting_transaction') ?
                                [
                                    'label' => 'Transaction',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [

                                        Yii::$app->user->can('ro_transaction') ? ['label' => 'Transactions', 'icon' => 'circle-o', 'url' => ['/transaction'],] : [],
                                        Yii::$app->user->can('ro_routing_slip') ? ['label' => 'Routing Slip', 'icon' => 'circle-o', 'url' => ['/dv-aucs/tracking-index'],] : [],
                                        Yii::$app->user->can('ro_process_dv') ? ['label' => 'Process Dv', 'icon' => 'circle-o', 'url' => ['/dv-aucs'],] : [],
                                        Yii::$app->user->can('ro_jev') ?  ['label' => 'Jev', 'icon' => 'circle-o', 'url' => ['/jev-preparation'],] : [],
                                        Yii::$app->user->can('ro_turn_arround_time') ? ['label' => 'Turn Arround Time', 'icon' => 'circle-o', 'url' => ['/dv-aucs/turnarround-time'],] : [],
                                        Yii::$app->user->can('ro_transmittal') ? ['label' => 'Transmittal', 'icon' => 'circle-o', 'url' => ['/transmittal'],] : [],
                                        Yii::$app->user->can('super-user') ? ['label' => 'Document Tracking', 'icon' => 'circle-o', 'url' => ['/document-tracker'],] : [],
                                        Yii::$app->user->can('ro_jev_bgn_bal') ? ['label' => 'JEV Beginning Balance', 'icon' => 'circle-o', 'url' => ['/jev-beginning-balance'],] : [],
                                        Yii::$app->user->can('ro_remit_pye') ? ['label' => 'Remittance Payee', 'icon' => 'circle-o', 'url' => ['/remittance-payee'],] : [],
                                        Yii::$app->user->can('ro_payroll') ? ['label' => 'Payroll', 'icon' => 'circle-o', 'url' => ['/payroll'],] : [],
                                        Yii::$app->user->can('ro_remitttance') ? ['label' => 'Remittance', 'icon' => 'circle-o', 'url' => ['/remittance'],] : [],
                                        Yii::$app->user->can('ro_alphalist') ? ['label' => 'Alphalist', 'icon' => 'circle-o', 'url' => ['/ro-alphalist'],] : [],
                                        Yii::$app->user->can('ro_liq_rpt') ? ['label' => 'Liquidation Report', 'icon' => 'circle-o', 'url' => ['/ro-liquidation-report'],] : [],

                                    ],
                                ],
                                // : [],

                                Yii::$app->user->can('super-user') ?  [
                                    'label' => 'Reports',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [

                                        Yii::$app->user->can('super-user') ?   ['label' => 'DV w/o File Link', 'icon' => 'circle-o', 'url' => ['/dv-aucs/no-file-link-dvs'],] : [],
                                        Yii::$app->user->can('ro_gen_led') ?   ['label' => 'General Ledger', 'icon' => 'circle-o', 'url' => ['/general-ledger'],] : [],
                                        Yii::$app->user->can('ro_gen_jour') ?   ['label' => 'General Journal', 'icon' => 'circle-o', 'url' => ['/general-journal'],] : [],
                                        Yii::$app->user->can('ro_adadj') ?   ['label' => 'ADADJ', 'icon' => 'circle-o', 'url' => ['/jev-preparation/adadj'],] : [],
                                        Yii::$app->user->can('ro_ckdj') ?   ['label' => 'CKDJ', 'icon' => 'circle-o', 'url' => ['/jev-preparation/ckdj'],] : [],
                                        Yii::$app->user->can('ro_trial_bal') ?   ['label' => 'Trial Balance', 'icon' => 'circle-o', 'url' => ['/trial-balance'],] : [],
                                        Yii::$app->user->can('ro_cns_trl_bal') ?   ['label' => 'ConsoTrial Balance', 'icon' => 'circle-o', 'url' => ['/conso-trial-balance'],] : [],
                                        Yii::$app->user->can('ro_sub_trl_bal') ?   ['label' => 'Sub Trial Balance', 'icon' => 'circle-o', 'url' => ['/sub-trial-balance'],] : [],
                                        Yii::$app->user->can('ro_cns_sub_trl_bal') ?   ['label' => 'Conso Sub Trial Balance', 'icon' => 'circle-o', 'url' => ['/conso-sub-trial-balance'],] : [],
                                        Yii::$app->user->can('ro_sub_led') ?   ['label' => 'Subsidiary Ledger', 'icon' => 'circle-o', 'url' => ['/jev-preparation/get-subsidiary-ledger'],] : [],
                                        Yii::$app->user->can('super-user') ?   ['label' => 'Detailed Financial Position', 'icon' => 'circle-o', 'url' => ['/report/detailed-financial-position'],] : [],
                                        Yii::$app->user->can('super-user') ?   ['label' => 'Conso Financial Position', 'icon' => 'circle-o', 'url' => ['/jev-preparation/consolidated-financial-position'],] : [],
                                        Yii::$app->user->can('super-user') ?   ['label' => 'Detailed F Performance', 'icon' => 'circle-o', 'url' => ['/jev-preparation/detailed-financial-performance'],] : [],
                                        Yii::$app->user->can('super-user') ?   ['label' => 'Conso F Performance', 'icon' => 'circle-o', 'url' => ['/jev-preparation/consolidated-financial-performance'],] : [],
                                        Yii::$app->user->can('super-user') ?   ['label' => 'Detailed Cashflow', 'icon' => 'circle-o', 'url' => ['/jev-preparation/detailed-cashflow'],] : [],
                                        Yii::$app->user->can('super-user') ?   ['label' => 'Conso Cashflow', 'icon' => 'circle-o', 'url' => ['/jev-preparation/consolidated-cashflow'],] : [],
                                        Yii::$app->user->can('super-user') ?   ['label' => 'Net Asset Changes', 'icon' => 'circle-o', 'url' => ['/jev-preparation/changes-netasset-equity'],] : [],
                                        Yii::$app->user->can('super-user') ?   ['label' => 'Transaction Archive', 'icon' => 'circle-o', 'url' => ['/report/transaction-archive'],] : [],
                                        Yii::$app->user->can('super-user') ?   ['label' => 'Remittance Summary', 'icon' => 'circle-o', 'url' => ['/report/withholding-and-remittance-summary'],] : [],
                                        Yii::$app->user->can('super-user') ?   ['label' => 'Annex 3 CA to Employees', 'icon' => 'circle-o', 'url' => ['/report/liquidation-report-annex'],] : [],

                                    ],
                                ] : ['label' => ''],
                            ],


                        ] : [],
                        Yii::$app->user->can('super-user') ?  [
                            'label' => 'Budget',
                            'url' => '#',
                            'items' => [
                                [
                                    'label' => 'Master Records',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'Responsibility Center', 'icon' => 'circle-o', 'url' => ['/responsibility-center'],],
                                        ['label' => 'Documet Recieve', 'icon' => 'circle-o', 'url' => ['/document-recieve'],],
                                        ['label' => 'Fund Cluster Code', 'icon' => 'circle-o', 'url' => ['/fund-cluster-code'],],
                                        ['label' => 'Financing Source Code', 'icon' => 'circle-o', 'url' => ['/financing-source-code'],],
                                        ['label' => 'Authorization Code', 'icon' => 'circle-o', 'url' => ['/authorization-code'],],
                                        ['label' => 'Fund Classification Code', 'icon' => 'circle-o', 'url' => ['/fund-category-and-classification-code'], 'options' => ['style' => 'color:red;']],
                                        ['label' => 'MFO/PAP Codes', 'icon' => 'circle-o', 'url' => ['/mfo-pap-code'],],
                                        ['label' => 'Fund Source', 'icon' => 'circle-o', 'url' => ['/fund-source'],],
                                        ['label' => 'Allotment Type', 'icon' => 'circle-o', 'url' => ['/allotment-type'],],

                                    ],
                                ],
                                [
                                    'label' => 'Transaction',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'Record Allotments', 'icon' => 'circle-o', 'url' => ['/record-allotments'],],
                                        ['label' => 'Process Ors', 'icon' => 'circle-o', 'url' => ['/process-ors'],],
                                        ['label' => 'Process BURS', 'icon' => 'circle-o', 'url' => ['/process-ors/burs-index'],],

                                    ],
                                ],
                                [
                                    'label' => 'Reports',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('super-user') ?     ['label' => 'SAOB', 'icon' => 'circle-o', 'url' => ['/saob'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'FUR', 'icon' => 'circle-o', 'url' => ['/ro-fur'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'RAO', 'icon' => 'circle-o', 'url' => ['/report/rao'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'FUR per MFO/PAP', 'icon' => 'circle-o', 'url' => ['/report/fur-mfo'],] : [],


                                    ],
                                ],
                            ],
                        ] : ['label' => ''],
                        Yii::$app->user->can('super-user') ?  [
                            'label' => 'Cash',
                            'url' => '#',
                            'items' => [
                                [
                                    'label' => 'Master Records',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Check Ranges', 'icon' => 'circle-o', 'url' => ['/ro-check-ranges'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Mode of Payments', 'icon' => 'circle-o', 'url' => ['/mode-of-payments'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Banks', 'icon' => 'circle-o', 'url' => ['/banks'],] : [],
                                    ],
                                ],
                                [
                                    'label' => 'Transaction',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'Cash Disbursement', 'icon' => 'circle-o', 'url' => ['/cash-disbursement'],],
                                        ['label' => 'SLIIE`s', 'icon' => 'circle-o', 'url' => ['/sliies'],],
                                        ['label' => 'LDDAP-ADA`s', 'icon' => 'circle-o', 'url' => ['/lddap-adas'],],
                                        ['label' => 'ACIC`s', 'icon' => 'circle-o', 'url' => ['/acics'],],
                                        ['label' => 'ACIC in Bank', 'icon' => 'circle-o', 'url' => ['/acic-in-bank'],],
                                        ['label' => 'RCI', 'icon' => 'circle-o', 'url' => ['/rci'],],
                                        ['label' => 'RADAI', 'icon' => 'circle-o', 'url' => ['/radai'],],
                                        ['label' => 'Cash Received', 'icon' => 'circle-o', 'url' => ['/cash-received'],],
                                        ['label' => 'Laps Amounts', 'icon' => 'circle-o', 'url' => ['/cash-adjustment'],],
                                        ['label' => 'Cancel Disbursement', 'icon' => 'circle-o', 'url' => ['/cash-disbursement/cancel-disbursement-index'],],

                                    ],
                                ],
                                [
                                    'label' => 'Reports',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('super-user') ?     ['label' => 'CADADR', 'icon' => 'circle-o', 'url' => ['/report/cadadr'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'CADADR per DV', 'icon' => 'circle-o', 'url' => ['/report/dv-cadadr'],] : [],
                                    ],
                                ],
                            ],
                        ] : ['label' => ''],
                        Yii::$app->user->can('report') ?    [
                            'label' => 'Report',
                            'url' => '#',
                            'items' => [

                                Yii::$app->user->can('super-user') ?     ['label' => 'Pending ORS', 'icon' => 'circle-o', 'url' => ['/report/pending-ors'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => "Pending DV's", 'icon' => 'circle-o', 'url' => ['/report/pending-dv'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'UnObligated Transaction', 'icon' => 'circle-o', 'url' => ['/report/unobligated-transaction'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'UnPaid Obligation', 'icon' => 'circle-o', 'url' => ['/report/unpaid-obligation'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Detailed Dv', 'icon' => 'circle-o', 'url' => ['/report/detailed-dv-aucs'],] : [],
                                Yii::$app->user->can('conso_dv') ?    ['label' => 'Conso Dv', 'icon' => 'circle-o', 'url' => ['/report/conso-detailed-dv'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Tax Remittance', 'icon' => 'circle-o', 'url' => ['/report/tax-remittance'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Annex 3', 'icon' => 'circle-o', 'url' => ['/report/annex3'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Annex A', 'icon' => 'circle-o', 'url' => ['/report/annex-a'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'RAAF', 'icon' => 'circle-o', 'url' => ['/report/raaf'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'CDJ', 'icon' => 'circle-o', 'url' => ['/report/cdj'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Transaction Tracking', 'icon' => 'circle-o', 'url' => ['/report/transaction-tracking'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'DV Time Monitoring', 'icon' => 'circle-o', 'url' => ['/report/dv-time-monitoring'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'DV Time Monitoring Summary', 'icon' => 'circle-o', 'url' => ['/report/dv-time-monitoring-summary'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Holidays', 'icon' => 'circle-o', 'url' => ['/holidays'],] : [],
                            ],
                        ] : [],
                        Yii::$app->user->can('super-user') ?    [
                            'label' => 'Property',
                            'url' => '#',
                            'items' => [
                                [
                                    'label' => 'Master Records',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('super-user') ?     ['label' => 'SSF SP No.', 'icon' => 'circle-o', 'url' => ['/ssf-sp-num'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'SSF SP Status', 'icon' => 'circle-o', 'url' => ['/ssf-sp-status'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Locations', 'icon' => 'circle-o', 'url' => ['/location'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Unit of Measure', 'icon' => 'circle-o', 'url' => ['/unit-of-measure'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Agency', 'icon' => 'circle-o', 'url' => ['/agency'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Employees', 'icon' => 'circle-o', 'url' => ['/employee'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Transfer Type', 'icon' => 'circle-o', 'url' => ['/transfer-type'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Property Articles', 'icon' => 'circle-o', 'url' => ['/property-articles'],] : [],
                                        ['label' => 'City/Municipality', 'icon' => 'circle-o', 'url' => ['/citymun'],],

                                    ],
                                ],
                                [
                                    'label' => 'Transactions',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Property', 'icon' => 'circle-o', 'url' => ['/property'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'PTR', 'icon' => 'circle-o', 'url' => ['/ptr'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'PAR', 'icon' => 'circle-o', 'url' => ['/par'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Property Card', 'icon' => 'circle-o', 'url' => ['/property-card'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Other Property Details', 'icon' => 'circle-o', 'url' => ['/other-property-details'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Depreciation Schedule', 'icon' => 'circle-o', 'url' => ['/depreciation-schedule'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'RLSDDP', 'icon' => 'circle-o', 'url' => ['/rlsddp'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'IIRUP', 'icon' => 'circle-o', 'url' => ['/iirup'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Derecognition', 'icon' => 'circle-o', 'url' => ['/derecognition'],] : [],

                                    ],
                                ],
                                [
                                    'label' => 'Reports',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Print PC Stickers', 'icon' => 'circle-o', 'url' => ['/property-card/print-pc'],] : [],
                                        ['label' => 'Property Database', 'icon' => 'circle-o', 'url' => ['/property/property-database'],],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'RPCPPE', 'icon' => 'circle-o', 'url' => ['/rpcppe'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'PPELC', 'icon' => 'circle-o', 'url' => ['/report/ppelc'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Accountabilities', 'icon' => 'circle-o', 'url' => ['/report/user-properties'],] : [],
                                    ],
                                ],



                                Yii::$app->user->can('super-user') ?     ['label' => 'Scan QR', 'icon' => 'circle-o', 'url' => ['/property-card/property-details'],] : [],

                                Yii::$app->user->can('super-user') ?     ['label' => 'Inventory', 'icon' => 'circle-o', 'url' => ['/inventory-report'],] : [],

                            ],
                        ] : [],
                        Yii::$app->user->can('procurement') ?    [
                            'label' => 'Procurement',
                            'url' => '#',
                            'items' => [
                                [
                                    'label' => 'Master Records',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        // Yii::$app->user->can('super-user') ?     ['label' => 'Stock Type', 'icon' => 'circle-o', 'url' => ['/pr-stock-type'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Contract Type', 'icon' => 'circle-o', 'url' => ['/pr-contract-type'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Mode of Procurment', 'icon' => 'circle-o', 'url' => ['/pr-mode-of-procurement'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Office/Section', 'icon' => 'circle-o', 'url' => ['/pr-office'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Stock/Property', 'icon' => 'circle-o', 'url' => ['/pr-stock'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'BAC', 'icon' => 'circle-o', 'url' => ['/bac-composition'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'BAC position', 'icon' => 'circle-o', 'url' => ['/bac-position'],] : [],
                                    ],
                                ],
                                [
                                    'label' => 'Transaction',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        // Yii::$app->user->can('super-user') ?     ['label' => 'Activity/Project Procurement', 'icon' => 'circle-o', 'url' => ['/pr-project-procurement'],] : [],
                                        Yii::$app->user->can('ppmp') ?     ['label' => 'Supplemental PPMP', 'icon' => 'circle-o', 'url' => ['/supplemental-ppmp'],] : [],
                                        Yii::$app->user->can('purchase_request') ?     ['label' => 'Purchase Request', 'icon' => 'circle-o', 'url' => ['/pr-purchase-request'],] : [],
                                        Yii::$app->user->can('rfq') ?     ['label' => 'RFQ', 'icon' => 'circle-o', 'url' => ['/pr-rfq'],] : [],
                                        Yii::$app->user->can('aoq') ?     ['label' => 'AOQ', 'icon' => 'circle-o', 'url' => ['/pr-aoq'],] : [],
                                        Yii::$app->user->can('purchase_order') ?     ['label' => 'Purchase Order', 'icon' => 'circle-o', 'url' => ['/pr-purchase-order'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Transmittal', 'icon' => 'circle-o', 'url' => ['/purchase-order-transmittal'],] : [],

                                    ],
                                ],
                                [
                                    'label' => 'Reports',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Procurement Summary', 'icon' => 'circle-o', 'url' => ['/report/procurement-summary'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'PO Summary', 'icon' => 'circle-o', 'url' => ['/report/pr-summary'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Procurement Search', 'icon' => 'circle-o', 'url' => ['/report/proc-summary'],] : [],
                                    ],
                                ],




                            ],
                        ] : [],
                        Yii::$app->user->can('super-user') ?    [
                            'label' => 'General Services',
                            'url' => '#',
                            'items' => [
                                [
                                    'label' => 'Master Records',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Cars', 'icon' => 'circle-o', 'url' => ['/cars'],] : [],
                                    ],
                                ],



                                Yii::$app->user->can('super-user') ?     ['label' => 'Job Request', 'icon' => 'circle-o', 'url' => ['/maintenance-job-request'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Pre-Repair Inspection', 'icon' => 'circle-o', 'url' => ['/pre-repair-inspection'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Trip Ticket', 'icon' => 'circle-o', 'url' => ['/trip-ticket'],] : [],

                            ],
                        ] : [],
                        Yii::$app->user->can('super-user') ?    [
                            'label' => 'Inspection',
                            'url' => '#',
                            'items' => [

                                Yii::$app->user->can('super-user') ?     ['label' => 'Request for Inspection', 'icon' => 'circle-o', 'url' => ['/request-for-inspection'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'Inspection Report', 'icon' => 'circle-o', 'url' => ['/inspection-report'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'IAR', 'icon' => 'circle-o', 'url' => ['/iar'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'IR Transmittal', 'icon' => 'circle-o', 'url' => ['/ir-transmittal'],] : [],
                                Yii::$app->user->can('super-user') ?     ['label' => 'IAR Transmittal', 'icon' => 'circle-o', 'url' => ['/iar-transmittal'],] : [],

                            ],
                        ] : [],
                        Yii::$app->user->can('province') ? [
                            'label' => 'Province',
                            'url' => '#',
                            'items' => [
                                [
                                    'label' => 'Master Records',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('province') ?  ['label' => 'Advances Report Types', 'icon' => 'circle-o', 'url' => ['/advances-report-types'],] : [],
                                        Yii::$app->user->can('province') ?  ['label' => 'Bank Account', 'icon' => 'circle-o', 'url' => ['/bank-account'],] : [],
                                        Yii::$app->user->can('po_check_range') ?  ['label' => 'Check Range', 'icon' => 'circle-o', 'url' => ['/check-range'],] : [],
                                        Yii::$app->user->can('po_asignatory') ?     ['label' => 'PO Asignatory', 'icon' => 'circle-o', 'url' => ['/po-assignatory'],] : [],
                                        Yii::$app->user->can('po_responsibility_center') ?     ['label' => 'PO Responsibility Center', 'icon' => 'circle-o', 'url' => ['/po-responsibility-center'],] : [],
                                    ],
                                ],
                                [
                                    'label' => 'Transactions',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('advances') ?     ['label' => 'Advances', 'icon' => 'circle-o', 'url' => ['/advances'],] : [],
                                        Yii::$app->user->can('po_transaction') ?     ['label' => 'PO Transaction', 'icon' => 'circle-o', 'url' => ['/po-transaction'],] : [],
                                        Yii::$app->user->can('liquidation') ?     ['label' => 'Liquidation', 'icon' => 'circle-o', 'url' => ["/liquidation"],] : [],
                                        Yii::$app->user->can('liquidation') ?     ['label' => 'Cancelled Check', 'icon' => 'circle-o', 'url' => ['/liquidation/cancelled-check-index'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Pending Transmittal', 'icon' => 'circle-o', 'url' => ['/po-transmittal/pending-at-ro'],] : [],
                                        Yii::$app->user->can('po_transmittal') ? ['label' => 'Transmittal', 'icon' => 'circle-o', 'url' => ['/po-transmittal'],] : [],
                                        Yii::$app->user->can('po_transmittal') ? ['label' => 'Returned DV`s', 'icon' => 'circle-o', 'url' => ['/po-transmittal/returned-liquidation'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'PO Transmittal to COA', 'icon' => 'circle-o', 'url' => ['/po-transmittal-to-coa'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Positions', 'icon' => 'circle-o', 'url' => ['/employee-position'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Alphalist', 'icon' => 'circle-o', 'url' => ['/alphalist'],] : [],
                                    ],
                                ],
                                [
                                    'label' => 'Reports',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('po_cibr') ?     ['label' => 'CIBR', 'icon' => 'circle-o', 'url' => ['/cibr'],] : [],
                                        Yii::$app->user->can('po_cdr') ?     ['label' => 'CDR', 'icon' => 'circle-o', 'url' => ['/cdr'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'FUR', 'icon' => 'circle-o', 'url' => ['/fur'],] : [],
                                        Yii::$app->user->can('province') ?     ['label' => 'ROD', 'icon' => 'circle-o', 'url' => ['/rod'],] : [],
                                        Yii::$app->user->can('fund_source_fur') ?     ['label' => 'Fund Source FUR', 'icon' => 'circle-o', 'url' => ['/report/fund-source-fur'],] : [],
                                        Yii::$app->user->can('summary_fund_source_fur') ?     ['label' => 'Summary Fund Source FUR', 'icon' => 'circle-o', 'url' => ['/report/summary-fund-source-fur'],] : [],
                                        Yii::$app->user->can('summary_fund_source_fur') ?     ['label' => 'Summary Budget Year FUR', 'icon' => 'circle-o', 'url' => ['/report/budget-year-fur'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'MLP', 'icon' => 'circle-o', 'url' => ['/monthly-liquidation-program'],] : [],
                                        Yii::$app->user->can('super-user') ?     ['label' => 'Adequacy of Resource', 'icon' => 'circle-o', 'url' => ['/report/province-adequacy'],] : [],
                                        Yii::$app->user->can('advances_liquidation') ?     ['label' => 'Advances/Liquidation', 'icon' => 'circle-o', 'url' => ['/report/advances-liquidation'],] : [],

                                        Yii::$app->user->can('super-user') ?     ['label' => 'PO Transmittal Summary', 'icon' => 'circle-o', 'url' => ['/report/po-transmittal-summary'],] : [],
                                    ],
                                ],










                            ],
                        ] : ['label' => ''],
                        Yii::$app->user->can('super-user') ?    [
                            'label' => 'IT Helpdesk',
                            'url' => '#',
                            'items' => [
                                [
                                    'label' => 'Transactions',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        Yii::$app->user->can('super-user') ?     ['label' => 'IT Maintenance Request', 'icon' => 'circle-o', 'url' => ['/it-maintenance-request'],] : [],
                                    ],
                                ],

                            ],
                        ] : [],
                        Yii::$app->user->can('super-user') ?     ['label' => 'Travel Order', 'icon' => 'circle-o', 'url' => ['/travel-order'],] : [],
                        Yii::$app->user->can('super-user') ?     ['label' => 'Supervisor Validation Notes', 'icon' => 'circle-o', 'url' => ['/supervisor-validation-notes'],] : [],




                    ],
                ]
            );
        }

        ?>

    </section>

</aside>
<script>
    $(document).ready(function() {
        $(".uploadImage").click(function(e) {
            e.preventDefault();
            $("#genericModal")
                .modal("show")
                .find("#modalContent")
                .load($(this).attr("href"));
        });
    })
</script>