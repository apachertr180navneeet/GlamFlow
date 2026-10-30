<!DOCTYPE html>
<html lang="en" ng-app="{{ config('app.name') }}" lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">
    <head>
        <meta charset="utf-8" />
        <title>{{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <meta name="description" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="ws_url" content="{{ env('WS_URL') }}">
        <meta name="user_id" content="{{ Auth::id() }}">
        <link rel="icon" type="image/x-icon" href="{{asset('assets/admin/img/favicon/favicon.ico')}}" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
        <link rel="stylesheet" href="{{asset('assets/admin/vendor/fonts/boxicons.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/admin/vendor/css/core.css')}}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{asset('assets/admin/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{asset('assets/admin/css/demo.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/admin/css/modern-admin.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrapDataTable.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/admin/vendor/libs/apex-charts/apex-charts.css')}}" />
        <script src="{{asset('assets/admin/vendor/js/helpers.js')}}"></script>
        <script src="{{asset('assets/admin/js/config.js')}}"></script>
        <link rel="stylesheet" href="{{asset('assets/admin/css/sweet-alert.css')}}" />
        @yield('style')
        <style>
            
        </style>
        
    </head>
    <body>
       <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                @include('admin.layouts.elements.left_sidebar')
                <div class="layout-page">
                    @include('admin.layouts.elements.header')
                    <div class="content-wrapper">
                        @yield('content')
                        @include('admin.layouts.elements.footer')
                        <div class="content-backdrop fade"></div>
                    </div>
                    @include('admin.layouts.elements.right_sidebar')
                </div>
        
                <script src="{{asset('assets/admin/vendor/libs/jquery/jquery.js')}}"></script>
                <script src="{{asset('assets/admin/vendor/libs/popper/popper.js')}}"></script>
                <script src="{{asset('assets/admin/vendor/js/bootstrap.js')}}"></script>
                <script src="{{asset('assets/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
                <script src="{{asset('assets/admin/vendor/js/menu.js')}}"></script>
                <script src="{{asset('assets/admin/vendor/libs/apex-charts/apexcharts.js')}}"></script>
                <script src="{{asset('assets/admin/js/main.js')}}"></script>
                <script src="{{asset('assets/admin/js/dataTable.js')}}"></script>
                <script src="{{asset('assets/admin/js/bootstrapDataTable.js')}}"></script>
                <script src="{{asset('assets/admin/js/dashboards-analytics.js')}}"></script>
                <script src="{{asset('assets/admin/js/moment.min.js')}}"></script>
                <script>
                    $.extend(true, $.fn.dataTable.defaults, {
                        lengthChange: false
                    });

                    // Fix dropdown cut-off in responsive tables & cards globally (Bootstrap & Select2)
                    $(document).on('show.bs.dropdown', '.dropdown', function () {
                        $(this).parents('.table-responsive, .card, .card-body').css('overflow', 'visible');
                    }).on('hide.bs.dropdown', '.dropdown', function () {
                        $(this).parents('.table-responsive, .card, .card-body').css('overflow', '');
                    });

                    $(document).on('select2:open', function (e) {
                        $(e.target).closest('.table-responsive, .card, .card-body').css('overflow', 'visible');
                    }).on('select2:close', function (e) {
                        $(e.target).closest('.table-responsive, .card, .card-body').css('overflow', '');
                    });

                    // Global Select2 responsiveness & tab switching fix
                    if (window.jQuery && $.fn.select2) {
                        $.fn.select2.defaults.set("width", "100%");
                    }

                    // When Bootstrap tabs are shown, recalculate Select2 inside active tab to prevent short/narrow boxes
                    $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"], a[data-bs-toggle="tab"]', function (e) {
                        var target = $(e.target).attr('data-bs-target') || $(e.target).attr('href');
                        if (target && $(target).length) {
                            $(target).find('.select2, select[data-select2-id]').each(function () {
                                if ($(this).data('select2')) {
                                    $(this).select2({ width: '100%' });
                                }
                            });
                        }
                    });

                    // When modals are shown, adjust Select2
                    $(document).on('shown.bs.modal', '.modal', function () {
                        $(this).find('.select2, select[data-select2-id]').each(function () {
                            if ($(this).data('select2')) {
                                $(this).select2({ width: '100%' });
                            }
                        });
                    });

                    // Re-adjust Select2 width on tablet orientation change / resize
                    $(window).on('resize orientationchange', function () {
                        $('.select2-container').css('width', '100%');
                    });


                    window.setupDependentBranchSelect = function(storeElem, branchElem) {
                        const $store = $(storeElem);
                        const $branch = $(branchElem);

                        if (!$store.length || !$branch.length) return;

                        let allOptions = $branch.data('all-options');
                        if (!allOptions) {
                            allOptions = [];
                            $branch.find('option').each(function() {
                                const sId = $(this).data('store-id') || $(this).data('store') || $(this).attr('data-store-id') || $(this).attr('data-store') || '';
                                allOptions.push({
                                    value: $(this).attr('value') !== undefined ? String($(this).attr('value')) : '',
                                    text: $(this).text(),
                                    storeId: String(sId),
                                    selected: $(this).is(':selected')
                                });
                            });
                            $branch.data('all-options', allOptions);
                        }

                        function filterOptions() {
                            const storeId = String($store.val() || '');
                            const currentVal = String($branch.val() || '');

                            $branch.empty();

                            let currentValValid = false;
                            allOptions.forEach(opt => {
                                if (!opt.value || !storeId || !opt.storeId || opt.storeId === storeId) {
                                    const $opt = $('<option>')
                                        .val(opt.value)
                                        .text(opt.text);

                                    if (opt.storeId) {
                                        $opt.attr('data-store-id', opt.storeId);
                                        $opt.attr('data-store', opt.storeId);
                                    }

                                    if (opt.value && opt.value === currentVal) {
                                        $opt.prop('selected', true);
                                        currentValValid = true;
                                    }

                                    $branch.append($opt);
                                }
                            });

                            if (!currentValValid && storeId) {
                                $branch.val('');
                            }

                            if ($.fn.select2 && $branch.data('select2')) {
                                $branch.trigger('change.select2');
                            }

                            $branch.trigger('change');
                        }

                        $store.off('change.depBranch').on('change.depBranch', filterOptions);
                        filterOptions();
                    };

                    window.setupDependentStaffSelect = function(storeElem, branchElem, staffElem) {
                        const $store = $(storeElem);
                        const $branch = $(branchElem);
                        const $staff = $(staffElem);

                        if (!$staff.length) return;

                        let allOptions = $staff.data('all-options');
                        if (!allOptions) {
                            allOptions = [];
                            $staff.find('option').each(function() {
                                const sId = $(this).data('store-id') || $(this).attr('data-store-id') || '';
                                const bId = $(this).data('branch-id') || $(this).attr('data-branch-id') || '';
                                allOptions.push({
                                    value: $(this).attr('value') !== undefined ? String($(this).attr('value')) : '',
                                    text: $(this).text(),
                                    storeId: String(sId),
                                    branchId: String(bId)
                                });
                            });
                            $staff.data('all-options', allOptions);
                        }

                        function filterStaff() {
                            const storeId = String($store.val() || '');
                            const branchId = String($branch.val() || '');
                            const currentVal = String($staff.val() || '');

                            $staff.empty();

                            let currentValValid = false;
                            allOptions.forEach(opt => {
                                let match = true;
                                if (opt.value) {
                                    if (storeId && opt.storeId && opt.storeId !== storeId) match = false;
                                    if (branchId && opt.branchId && opt.branchId !== branchId) match = false;
                                }
                                if (match) {
                                    const $opt = $('<option>')
                                        .val(opt.value)
                                        .text(opt.text);

                                    if (opt.storeId) $opt.attr('data-store-id', opt.storeId);
                                    if (opt.branchId) $opt.attr('data-branch-id', opt.branchId);

                                    if (opt.value && opt.value === currentVal) {
                                        $opt.prop('selected', true);
                                        currentValValid = true;
                                    }

                                    $staff.append($opt);
                                }
                            });

                            if (!currentValValid && (storeId || branchId)) {
                                $staff.val('');
                            }

                            if ($.fn.select2 && $staff.data('select2')) {
                                $staff.trigger('change.select2');
                            }
                        }

                        if ($store.length) $store.off('change.depStaff').on('change.depStaff', filterStaff);
                        if ($branch.length) $branch.off('change.depStaff').on('change.depStaff', filterStaff);
                        filterStaff();
                    };

                    $(document).ready(function() {
                        function autoBindStoreBranch() {
                            const pairs = [
                                ['#dashboardStoreSelect', '#dashboardBranchSelect'],
                                ['#storeFilter', '#branchFilter'],
                                ['#calendarStoreSelect', '#calendarBranchSelect'],
                                ['#expenseFilterStore', '#expenseFilterBranch'],
                                ['#expenseModalStore', '#expenseModalBranch'],
                                ['#reportStoreSelect', '#reportBranchSelect'],
                                ['#custReportStore', '#custReportBranch'],
                                ['#expReportStore', '#expReportBranch'],
                                ['#pkgReportStore', '#pkgReportBranch'],
                                ['#ledgerReportStore', '#ledgerReportBranch'],
                                ['#store_id', '#branch_id']
                            ];

                            pairs.forEach(pair => {
                                if ($(pair[0]).length && $(pair[1]).length) {
                                    window.setupDependentBranchSelect(pair[0], pair[1]);
                                }
                            });

                            if ($('#staffFilter').length) {
                                window.setupDependentStaffSelect('#storeFilter', '#branchFilter', '#staffFilter');
                            }

                            $('form, .modal-content, .card-body').each(function() {
                                const $container = $(this);
                                const $s = $container.find('select[name="store_id"]');
                                const $b = $container.find('select[name="branch_id"]');
                                if ($s.length && $b.length) {
                                    window.setupDependentBranchSelect($s[0], $b[0]);
                                }
                            });
                        }

                        autoBindStoreBranch();
                    });
                </script>
                @yield('script')
                @include('admin.layouts.elements.sweet_alerts')
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
    </body>
</html>