<div class="tab-pane {{ ($selectedTab == 1) ? 'active' : '' }}" id="dailywork" role="tabpanel">
    <div class="row">
        <div class="col-xl-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Daily Work Log</h4>
                <div class="d-sm-flex  align-items-center filter-with-search">
                    <div class="d-sm-flex">
                        <div class="form-group mb-0 me-3">
                            @include('filters.user_type_filter')
                        </div>
                        <div class="form-group mb-0 me-3">
                            @include('filters.month_filter')
                        </div>
                        <div class="form-group mb-0">
                            @include('filters.single_year_filter')
                        </div>
                        <div class="form-group mb-0  ms-sm-3">
                            @if(in_array(\Auth::user()->userRole->code, ['ADMIN', 'PM']))
                                @include('filters.team_filter')
                            @else
                            <select class="form-control select2" name="team" id="team" disabled>
                                <option value="{{ Auth::user()->officialUser->userTeam->id }}" selected>{{
                                    Auth::user()->officialUser->userTeam->name }}</option>
                            </select>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('resource-managment.partials.common-worklog')
</div>

