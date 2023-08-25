@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('expected-revenue') }}
@endsection
@section('content')
<div class="row mb-3">
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title($module_title) }}</h4>
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body bg-body">
                <div class=" ">
                    <table id="indexDataTable"
                        class="project-list-table table nowrap table-borderless w-100 align-middle custom-table dataTable no-footer dtr-inline">
                        <thead>
                            <tr>
                                <th>Project Name </th>
                                @for($i=0;$i<3;$i++)
                                <th style="text-align: center">{{date('M Y',strtotime($i.' month'));}}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenues as $revenue)
                                <tr>
                                    @php
                                        $exptRevenue = $revenue->pluck('expected_revenue','month')->toArray();
                                    @endphp
                                    <td> {{$revenue[0]->project->name ?? ''}} </td>
                                    @foreach($months as $month)
                                        @php
                                            $amount = $exptRevenue[$month] ?? '' ;
                                        @endphp                                   
                                        <td class="amount-align"> {{ ($amount != '') ? number_format($amount,2,'.',',') : '-' }} </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection
@push('scripts')

@endpush
