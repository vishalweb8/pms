<div class="modal-body"> 
    @php 
        $isTrue = false;
        if(request()->route()->getName() == 'organizationChart.edit') {
            $isTrue = true;
        }
    @endphp
    <div class="form-group mb-3">
        {!! Form::label('user_id', 'Select User', ['class' => 'col-form-label pt-0'])
        !!}
        {!! Form::select('user_id', $users, null, ['class' => 'form-control
         form-group select2', 'id'=> "user_id",'disabled' => $isTrue]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('designation_id', 'Designation', ['class' => 'col-form-label pt-0'])
        !!}
        {!! Form::select('designation_id', $designations, null, ['class' => 'form-control
            form-group select2', 'id'=> "designation_id",'disabled' => 'true']) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('team_id', 'Team', ['class' => 'col-form-label pt-0']) !!}
        {!! Form::select('team_id', $teams, null, ['class' => 'form-control form-group select2', 'id'=>
        "team_id",'disabled' => 'true']) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('is_top_level', 'Top Level', ['class' => 'col-form-label pt-0']) !!}
        <div class="radio-container d-flex align-items-center flex-wrap">
            {!! Form::radio('is_top_level', 1, null, ['class' => 'form-check-input mt-0 me-2']) !!}
            {!! Form::label('yes', 'Yes', ['class' => 'form-check-label me-2']) !!}

            {!! Form::radio('is_top_level', 0, 1, ['class' => 'form-check-input mt-0 me-2']) !!}
            {!! Form::label('no', 'No', ['class' => 'form-check-label me-2']) !!}
        </div>
    </div>    
    <div class="form-group mb-3">
        {!! Form::label('reporting_to', 'Reporting To', ['class' => 'col-form-label pt-0'])
        !!}
        {!! Form::select('reporting_to', $reportings, $reportingTo ?? null, ['multiple'=>'multiple', 'name' => 'reporting_to[]','class' => 'form-control select2-multiple form-select2', 'id'=> "reporting_to"]) !!}
    </div>
    <div class="form-group mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="status-chart">Status</label>
            <input class="form-check-input" type="checkbox" name="status" id="status-chart" value="1" {{ (isset($organizationChart) && $organizationChart->status) ? 'checked' : (isset($organizationChart) ? '' : 'checked') }}>
        </div>
    </div>
</div>
