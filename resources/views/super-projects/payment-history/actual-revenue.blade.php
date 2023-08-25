@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('actual-revenue') }}
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
                                @for($i=2;$i>=0;$i--)
                                <th>{{date('M Y',strtotime('-'.$i.' month'));}}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                
                                <tr>
                                    <td>{{$project[0]->project->name ?? '-'}} </td>
                                    @for($i=2;$i>=0;$i--)
                                        @php
                                            $monthYear = date('m-Y',strtotime('-'.$i.' month'));
                                            $revenue = $project->pluck('actual_revenue','month_year')->toArray();
                                        @endphp
                                        <td>{{                                                 
                                                (isset($revenue[$monthYear])) ? number_format($revenue[$monthYear],2) : '-';
                                            }}
                                        </td>
                                    @endfor
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
