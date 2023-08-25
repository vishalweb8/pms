@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('actual-vs-expected') }}
@endsection
@section('content')
<div class="row mb-3">
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title($module_title) }}</h4>
            <div class="d-sm-flex  align-items-center filter-with-search all-employee-leave-index">
                <div class="form-group mb-0 me-3">
                    <select class="form-control select2"  name="project" id="project">
                        <option value="" selected >All Projects</option>
                        @forelse ( $projectsFilters as $project)
                        <option value="{{ $project['id'] }}">{{ $project['project_code']." - ".$project['name'] }}
                        </option>
                        @empty
                        <option value="" disabled>No Project Assigned</option>
                        @endforelse
                    </select>
                </div>
                <div class="form-group mb-0 me-3">
                    @include('filters.month_filter')
                </div>
                <div class="form-group mb-0">
                    @include('filters.single_year_filter')
                </div>
                <div class="form-group mb-0 me-3">
                    <label class="custom-filter cursor-pointer" style="color: rgb(74, 74, 240)"> Or Custom Filters </label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body bg-body">
                <canvas id="revenueChart" width="300" height="100" @empty($projects)
                   style="display: none;" 
                @endempty></canvas>
                <div id="data-not-avl" @empty(!$projects)
                style="display: none;" 
             @endempty>
                    @include('common.data-not-available')
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
<div class="modal fade" tabindex="-1" id="filter-modal" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select custom filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="custom-form-filter">
                    <div class="radio-container d-flex align-items-center flex-wrap mb-3">
                        <input type="radio" class="form-check-input mt-0 me-2" name="custom_filter" value="last_3_month" /> <label class="form-check-label me-2"> Last 3 months </label>
                        <input type="radio" class="form-check-input mt-0 me-2" name="custom_filter" value="last_6_month" /> <label class="form-check-label me-2">Last 6 months </label>
                        <input type="radio" class="form-check-input mt-0 me-2" name="custom_filter" value="current_year" /> <label class="form-check-label me-2">This Year </label>
                    </div>
                    <div class="radio-container d-flex align-items-center flex-wrap mb-3">
                        <input type="radio" class="form-check-input mt-0 me-2" name="custom_filter" value="date_range" /> <label class="form-check-label me-2"> Date Range</label>
                    </div>
                    
                    <div class="row date-range-row" style="display: none">
                        <div class="col-6">
                            <div class="input-group mb-3">
                                {!! 
                                    Form::text('start_date', null, ['class' => 'form-control  date-picker', 'placeholder' => 'Select Start Date', 'id' => 'start_date', 'data-date-format' => "dd-mm-yyyy", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"])
                                    !!} 
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span> 
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                {!! 
                                    Form::text('end_date', null, ['class' => 'form-control date-picker', 'placeholder' => 'Select End Date', 'id' => 'end_date', 'data-date-format' => "dd-mm-yyyy", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"])
                                !!} 
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span> 
                            </div>
                        </div>
                    </div>
                </form>            
            </div>
            <div class="modal-footer">
                <button id="reset-btn" type="reset" class="btn btn-secondary waves-effect">Reset</button>
                <button  id="filter"  type="button" class="btn btn-primary waves-effect waves-light">Filters</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('/js/chart.min.js') }}"></script>
<script>
    jQuery(function() {
        // remove and add option for months in filter
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $("#daily-task-month").find("option").remove();

        $.each(monthNames, function (index, value) {
            $("#daily-task-month").append(
                $(document.createElement("option")).prop({
                    value: index + 1,
                    text: value,
                })
            );
        });

        var dt = new Date();
        var month = ("0" + (dt.getMonth() + 1)).slice(-2);
        $("#daily-task-month").val(parseInt(month)).select2();


        // for bar chart actual v/s expected revenue

        var datasets = [{
                label: 'Actual Revenue ($)',
                data: @json($actualRevenue),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1,
            },
            {
                label: 'Expected Revenue ($)',
                data: @json($expectedRevenue),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1,
            }];

        const ctx = $('#revenueChart');
        const revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($projects),                
                datasets: datasets
            },
            options: {
                responsive: true,                
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },                    
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        $("#start_date").datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
        }).on('changeDate', function (selected) {
            var nextDay = new Date(selected.date);
                nextDay.setDate(nextDay.getDate() + 1);
            $('#end_date').datepicker('setStartDate', nextDay);
        });

        $("#end_date").datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
        }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('#start_date').datepicker('setEndDate', minDate);
        });

        $('#custom-form-filter .form-check-input').click(function() {
            $('#start_date,#end_date').val('');
            if($(this).val() == 'date_range') {
                $(".date-range-row").show();
            } else {
                $(".date-range-row").hide();
            }
        });
        $('.custom-filter').click(function() {
            $("#filter-modal").modal('show');
        });
        $('#filter').click(function() {
            getChartData();
            $("#filter-modal").modal('hide');
        });
        $('#reset-btn').click(function() {
            $("#custom-form-filter").trigger('reset');
            $(".date-range-row").hide();
            getChartData();
        });

        $('#financialSingleYear,#daily-task-month,#project').change(function() {
            getChartData();            
        });

        function getChartData() {
            $.ajax({
                url: "{{route('actual.vs.expected')}}",
                type: "GET",
                data: {
                    year: $("#financialSingleYear").val(),
                    month: $("#daily-task-month").val(),
                    project: $("#project").val(),
                    start_date: $("#start_date").val(),
                    end_date: $("#end_date").val(),
                    custom_filter: $('input[name="custom_filter"]:checked').val(),
                },
                success: function (response) {
                    if(response.status) {
                        $("#data-not-avl").hide();
                        ctx.show();
                        let labels = response.data.projects;
                        datasets[0].data = response.data.actualRevenue;
                        datasets[1].data = response.data.expectedRevenue;
                        updateChartData(labels, datasets);
                    } else {
                        ctx.hide();
                        $("#data-not-avl").show();
                    }
                },
                error: function (response) {
                    toastr.error(response.message);
                },
            });
        }

        function updateChartData(labels, datasets) {
            revenueChart.data.labels = labels;
            revenueChart.data.datasets = datasets;
            revenueChart.update();
        }
        
    });



</script>
@endpush
