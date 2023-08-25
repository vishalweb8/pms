<div class="modal-body"> 
    <div class="form-group mb-3">
        {!! Form::label('user_id', 'Select User', ['class' => 'col-form-label pt-0'])
        !!}
        {!! Form::select('user_id', $users, null, ['class' => 'form-control
         form-group select2', 'id'=> "user_id", 'disabled' => 'true']) !!}
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
        {!! Form::label('month', 'Month-Year', ['class' => 'col-form-label pt-0']) !!}
        {!! Form::text('month_year', null, ['class' => 'form-control', 'autocomplete' => 'off','disabled' => 'true']) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('revenue', 'Revenue($)', ['class' => 'col-form-label pt-0']) !!}
        {!! Form::text('revenue', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "revenue", 'placeholder'=> "Enter Revenue"]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('expense', 'Expense($)', ['class' => 'col-form-label pt-0']) !!}
        {!! Form::text('expense', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "expense", 'placeholder'=> "Enter Expense"]) !!}
    </div>
    <div class="form-group mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="status-chart">Status</label>
            <input class="form-check-input" type="checkbox" name="status" id="status-chart" value="1" {{ (isset($organizationChart) && $organizationChart->status) ? 'checked' : (isset($organizationChart) ? '' : 'checked') }}>
        </div>
    </div>
</div>
