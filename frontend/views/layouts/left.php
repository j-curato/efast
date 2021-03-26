<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..." />
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <style>
            .shit {
                color: red;
            }
        </style>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree text-truncate', 'data-widget' => 'tree', 'style' => 'width: inherit'],
                'items' => [
                    ['label' => 'Gii', 'icon' => 'book', 'url' => ['/responsibility-center'],],

                    [
                        'label' => 'Budget',
                        'icon' => 'share',
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
                                ],
                            ],
                            [
                                'label' => 'Transaction',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Record Allotments', 'icon' => 'circle-o', 'url' => ['/record-allotments'],],
                                    ['label' => 'Process Ors', 'icon' => 'circle-o', 'url' => ['/process-ors-entries'],],
                                    ['label' => 'Process BURS', 'icon' => 'circle-o', 'url' => ['/process-burs'],],
                             
                                ],
                            ],
                        ],


                    ],
                    [
                        'label' => 'Accounting',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
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


                                ],
                            ],
                            [
                                'label' => 'Transaction',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Transactions', 'icon' => 'circle-o', 'url' => ['/transaction'],],
                                    ['label' => 'Jev', 'icon' => 'circle-o', 'url' => ['/jev-preparation'],],
                                  

                                ],
                            ],
                            [
                                'label' => 'Reports',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                               
                                    ['label' => 'General Ledger', 'icon' => 'circle-o', 'url' => ['/jev-preparation/general-ledger'],],
                                    ['label' => 'General Journal', 'icon' => 'circle-o', 'url' => ['/jev-preparation/general-journal'],],
                                    ['label' => 'ADADJ', 'icon' => 'circle-o', 'url' => ['/jev-preparation/adadj'],],
                                    ['label' => 'CKDJ', 'icon' => 'circle-o', 'url' => ['/jev-preparation/ckdj'],],
                                    ['label' => 'Trial Balance', 'icon' => 'circle-o', 'url' => ['/jev-preparation/trial-balance'],],
                                    ['label' => 'Subsidiary Ledger', 'icon' => 'circle-o', 'url' => ['/jev-preparation/get-subsidiary-ledger'],],
                                    ['label' => 'Detailed Financial Position', 'icon' => 'circle-o', 'url' => ['/jev-preparation/detailed-financial-position'],],
                                    ['label' => 'Consolidated Financial Position', 'icon' => 'circle-o', 'url' => ['/jev-preparation/consolidated-financial-position'],],
                                    ['label' => 'Detailed F Performance', 'icon' => 'circle-o', 'url' => ['/jev-preparation/detailed-financial-performance'],],
                                    ['label' => 'Consolidated F Performance', 'icon' => 'circle-o', 'url' => ['/jev-preparation/consolidated-financial-performance'],],
                                    ['label' => 'Detailed Cashflow', 'icon' => 'circle-o', 'url' => ['/jev-preparation/detailed-cashflow'],],
                                    ['label' => 'Consolidated Cashflow', 'icon' => 'circle-o', 'url' => ['/jev-preparation/consolidated-cashflow'],],
                                    ['label' => 'Net Asset Changes', 'icon' => 'circle-o', 'url' => ['/jev-preparation/changes-netasset-equity'],],

                                ],
                            ],
                        ],


                    ],




                    // ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    // ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    // ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    // [
                    //     'label' => 'Some tools',
                    //     'icon' => 'share',
                    //     'url' => '#',
                    //     'items' => [
                    //         ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                    //         ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                    //         [
                    //             'label' => 'Level One',
                    //             'icon' => 'circle-o',
                    //             'url' => '#',
                    //             'items' => [
                    //                 ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                    //                 [
                    //                     'label' => 'Level Two',
                    //                     'icon' => 'circle-o',
                    //                     'url' => '#',
                    //                     'items' => [
                    //                         ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                    //                         ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                    //                     ],
                    //                 ],
                    //             ],
                    //         ],
                    //     ],
                    // ],
                ],
            ]
        ) ?>

    </section>

</aside>