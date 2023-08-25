
<div class="tab-pane {{ ($selectedTab == 2) ? 'active' : '' }}" id="allempwork" role="tabpanel">
    <div class="row">
        <div class="col-xl-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                {{-- <h4 class="mb-sm-0 font-size-18"></h4> --}}
                <div class="d-sm-flex  align-items-center filter-with-search">
                    <div class="d-sm-flex align-items-baseline">
                        @include('filters.date_range_filter')
                        <div class="form-group mb-0  ms-sm-3">
                            @if(in_array(\Auth::user()->userRole->code, ['ADMIN', 'PM']))
                            @include('filters.employee_filter')
                            @endif
                        </div>
                        <div class="form-group mb-0  ms-sm-3">
                            @if(in_array(\Auth::user()->userRole->code, ['ADMIN', 'PM']))
                                <select class="form-control select2" name="projects[]" id="projects" data-placeholder=
                                "Select Projects" multiple>
                                    @foreach($projects as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="form-group mb-0  ms-sm-3">
                            @if(in_array(\Auth::user()->userRole->code, ['ADMIN', 'PM']))
                                @include('filters.team_filter')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-2">
            <div class="card emp-list-section ">
                <div class="card-body">
                    <div class="table-responsive fixed-header">
                        <table class="table table-centered table-bordered mb-0 table-hover" >
                            <thead>
                                <tr>
                                    <th class="stick-column">Employees
                                    </th>
                                    <th class="stick-column " style="width: 208px">Projects</th>
                                    <th class="stick-column ">Worklogs</th>
                                    <th class="stick-column ">Total</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="filterdWorkLog"></tbody>
                        </table>
                    </div>
                    <!-- Data Loader -->
                    <div class="auto-load text-center">
                        <svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                            y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                            <path fill="#000"
                                d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50"
                                    to="360 50 50" repeatCount="indefinite" />
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}
