<div id="official_details">
    <div class="card card-user-profile-info">
        <div class="card-body">
            <div class="row">
                @php
                    $hide = '';
                    $disabled = 'disabled';
                    if(request()->route()->getName() == 'user.profile.edit.office' && (!in_array(\Auth::user()->userRole->code, ['ADMIN', 'HR', 'HRA']))) {
                        $hide = 'hide';
                    }
                    if(in_array(\Auth::user()->userRole->code, ['ADMIN'])){
                        $disabled = '';
                    }
                @endphp
                @if(Auth::user()->isManagement())
                <div class="col-lg-6 col-xxl-4 {{$hide}}">
                    <div class="form-group mb-3">
                        {!! Form::label('emp_code', 'Employee Code', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('emp_code', null, ['class' => 'form-control', 'id'=> "emp_code", 'placeholder'=>
                        "Enter Employee code"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4 {{$hide}}">
                    <div class="form-group mb-3">
                        {!! Form::label('experience', 'Total Previous Experience (Years)', ['class' => 'col-form-label pt-0'])
                        !!}
                        {!! Form::text('experience', null, ['class' => 'form-control', 'id'=> "experience",
                        'placeholder'=> "Enter Total Experience in Years", 'readonly' => 'true']) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4 {{$hide}}">
                    <div class="mb-3">
                        {!! Form::label('joining_date', 'Joining Date', ['class' => 'col-form-label pt-0']) !!}
                        <div class="input-group">
                            {!! Form::text('joining_date', null, ['class' => 'form-control ',
                            'id' => 'joining_date', 'data-date-format' => "yyyy-mm-dd", 'data-provide' =>
                            'datepicker','data-date-autoclose'=> "true", 'placeholder' => 'Joining Date']) !!}
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4 {{$hide}}">
                    <div class="mb-3">
                        {!! Form::label('confirmation_date', 'Confirmation date', ['class' => 'col-form-label pt-0'])
                        !!}
                        <div class="input-group">
                            {!! Form::text('confirmation_date', null, ['class' => 'form-control ', 'placeholder' =>
                            'Select Confirmation date', 'id' =>
                            'confirmation_date', 'data-date-format' => "yyyy-mm-dd", 'data-provide' =>
                            'datepicker','data-date-autoclose'=> "true"]) !!}
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4 {{$hide}}">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label for="task_entry_date" class="col-form-label pt-0">
                                Task Entry date
                            </label>
                            <div class="form-check form-switch form-switch-md" dir="ltr">
                                <label class="form-check-label" for="sod_eod_enabled">SOD/EOD Enabled</label>
                                <input class="form-check-input"  type="checkbox" name="sod_eod_enabled" id="sod_eod_enabled"
                                            {{ (isset($user) && $user->sod_eod_enabled) ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="input-group">
                            {!! Form::text('task_entry_date', null, ['class' => 'form-control ', 'placeholder' =>
                            'Select Task Entry date', 'id' =>
                            'task_entry_date', 'data-date-format' => "yyyy-mm-dd", 'data-provide' =>
                            'datepicker','data-date-autoclose'=> "true"]) !!}
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4 {{$hide}}">
                    <div class="mb-3">
                        {!! Form::label('team_id', 'Select Team', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::select('team_id', $teams, null, ['class' => 'form-control form-select2', 'id'=>
                        "team_id"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4 {{$hide}}">
                    <div class="form-group mb-3">
                        {!! Form::label('offered_ctc', 'Offered CTC', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::number('offered_ctc', null, ['class' => 'form-control', 'id'=> "offered_ctc",
                        'placeholder'=> "Enter Offered CTC"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4 {{$hide}}">
                    <div class="form-group mb-3">
                        {!! Form::label('current_ctc', 'Current CTC', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::number('current_ctc', null, ['class' => 'form-control', 'id'=> "current_ctc",
                        'placeholder'=> "Enter Current CTC"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4 {{$hide}}">
                    <div class="mb-3">
                        {!! Form::label('department_id', 'Select Department', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::select('department_id', $departments, null, ['class' => 'form-control form-select2',
                        'id'=> "department_id"]) !!}
                    </div>
                </div>
                @endif
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('skype_id', 'Skype Email ID', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::email('skype_id', null, ['class' => 'form-control', 'id'=> "skype_id",
                        'placeholder'=> "Enter skype ID"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('company_email_id', 'Company Email ID', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::email('company_email_id', null, ['class' => 'form-control', 'id'=> "company_email_id",
                        'placeholder'=> "Enter company email id" ,'readonly']) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('company_gmail_id', 'Company Gmail ID', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::email('company_gmail_id', null, ['class' => 'form-control', 'id'=> "company_gmail_id",
                        'placeholder'=> "Enter company Gmail ID"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('company_gitlab_id', 'Company Gitlab ID', ['class' => 'col-form-label pt-0'])
                        !!}
                        {!! Form::email('company_gitlab_id', null, ['class' => 'form-control', 'id'=>
                        "company_gitlab_id",
                        'placeholder'=> "Enter company Gitlab ID"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('company_github_id', 'Company Github ID', ['class' => 'col-form-label pt-0'])
                        !!}
                        {!! Form::email('company_github_id', null, ['class' => 'form-control', 'id'=>
                        "company_github_id",
                        'placeholder'=> "Enter company Github ID"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('technologies_ids', 'Select Technology', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::select('technologies_ids', $technologies, (isset($selectedTechnology)) ?
                        $selectedTechnology : null, ['multiple'=>'multiple', 'name' => 'technologies_ids[]','class' =>
                        'form-select select2-multiple form-select2', 'id'=> "technologies_ids"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4 {{$hide}}">
                    <div class="form-group mb-3">
                        {!! Form::label('reporting_ids', 'Select Reporting persons', ['class' => 'col-form-label pt-0'])
                        !!}
                        {!! Form::select('reporting_ids', $reportings,(isset($selectedReportings)) ?
                        $selectedReportings : null, ['multiple'=>'multiple', 'name' => 'reporting_ids[]', 'class' =>
                        'form-control
                        select2-multiple form-select2', 'id'=> "reporting_ids"]) !!}
                    </div>
                </div>
                @if(request()->route()->getName() != 'user.profile.edit.office')
                <div class="col-lg-12 col-xxl-4 tl-section">
                    <div class="form-group mb-3">
                        {!! Form::label('team_leaders', "Select Members", ['class' => 'col-form-label pt-0'])
                        !!}
                        {!! Form::select('team_leaders', $members ?? [],$selectedMembers ?? null, ['multiple'=>'multiple', 'name' => 'team_leaders[]', 'class' =>
                        'form-control
                        select2-multiple form-select2', 'id'=> "members_mentor", $disabled]) !!}
                    </div>
                </div>
                <div class="col-lg-12 col-xxl-4 tl-member-section">
                    <div class="form-group mb-3">
                        {!! Form::label('tl_members', "Select Mentors", ['class' => 'col-form-label pt-0'])
                        !!}
                        {!! Form::select('tl_members', $mentors ?? [],$selectedMentors ?? null, ['multiple'=>'multiple', 'name' => 'tl_members[]', 'class' =>
                        'form-control
                            select2-multiple form-select2', 'id'=> "tl_mentors",$disabled]) !!}
                        </div>
                    </div>
                </div>
                @endif
                @if(Auth::user()->isManagement() && request()->route()->getName() == 'user.edit')
                <div class="col-lg-12 col-xxl-4 tl-section">
                    <div class="form-group mb-3">
                        {!! Form::label('resigned_date', 'Exit Date', ['class' => 'col-form-label pt-0']) !!}
                        <div class="input-group">
                            {!! Form::text('resigned_date', null, ['class' => 'form-control ',
                            'id' => 'resigned_date', 'data-date-format' => "yyyy-mm-dd", 'data-provide' =>
                            'datepicker','data-date-autoclose'=> "true", 'placeholder' => 'Exit Date']) !!}
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>
                @endif

        </div>
    </div>
</div>
