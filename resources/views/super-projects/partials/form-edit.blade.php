<div class="row">
    <div class="col-md-6 form-group">
        {!! Form::label('floatingSelectGrid', 'Project Name', ['class' => 'col-form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'id'=> "technology",
        'placeholder'=> "Enter Project Name"]) !!}
    </div>

    {{-- Uncomment below if when start auto generate project code --}}
    {{-- @if(!isset($add_form)) --}}
    <div class="col-md-6 form-group">
        {!! Form::label('floatingSelectGrid', 'Project Code', ['class' => 'col-form-label']) !!}
        {!! Form::number('project_code', null, ['class' => 'form-control', 'id'=> "project_code",
        'placeholder'=> "Enter Project Code"])
        !!}
    </div>
    {{-- @endif --}}
    <div class="col-md-6 form-group">
        {!! Form::label('client_id', 'Client', ['class' => 'col-form-label']) !!}
        {!! Form::select('client_id',$clients,null, ['class' => 'form-control select2', 'id' => 'client_id']) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('payment_type_id', 'Payment Type', ['class' => 'col-form-label']) !!}
        {!! Form::select('payment_type_id',$paymentTypes,null, ['class' => 'form-control select2', 'id' =>
        'payment_type_id']) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('amount', 'Amount', ['class' => 'col-form-label']) !!}
        {!! Form::text('amount', null, ['class' => 'form-control', 'id'=> "amount", 'placeholder'=> "Enter Amount"]) !!}
        {{-- <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter Amount"
            value="{{ $project->amount }}"> --}}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('currency', 'Currency', ['class' => 'col-form-label']) !!}
        {!! Form::select('currency',$currencies, null, ['class' => 'form-control select2', 'id' => 'currency']) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('start_date', 'Start Date', ['class' => 'col-form-label']) !!}
        <div class="input-group">
            {!! Form::text('start_date', null, ['class' => 'form-control ', 'placeholder' => 'Select Start Date', 'id'
            =>
            'start_date', 'data-date-format' => "Y-m-d", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"])
            !!}
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('end_date', 'End Date', ['class' => 'col-form-label']) !!}
        <div class="input-group">
            {!! Form::text('end_date', null, ['class' => 'form-control ', 'placeholder' => 'Select End Date', 'id' =>
            'end_date', 'data-date-format' => "Y-m-d", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"])
            !!}
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-6 form-group mt-4">
        <div class="form-check form-switch form-switch-md">
            {!! Form::label('all_members', 'All Members', ['class' => 'col-form-label pe-2']) !!}
            {!! Form::checkbox('all_members', $allMembers, ($allMembers=="0" ? '' : 'checked'), ['id' => 'all_members',
            'class' => 'form-check-input']) !!}
        </div>
    </div>
    <div class="col-md-12 form-group">
        {!! Form::label('members_id', 'Members', ['class' => 'col-form-label']) !!}
        {!! Form::select('members_ids[]',$members,(!empty($selected_members) ? $selected_members : null),['multiple' => 'multiple','class' => 'form-control select2', 'id' => 'members_id', ($allMembers=='1' ?
        'disabled' : '')]) !!}
    </div>
    <div class="col-md-12 form-group">
        {!! Form::label('floatingSelectGrid', 'Project Description for Admin', ['class' => 'col-form-label']) !!}
        {!! Form::textArea('description', null, ['class' => 'form-control init-ck-editor', 'id'=> "description",
        'placeholder'=> "Enter Project Description" , 'rows' => 4])
        !!}
    </div>
    <div class="col-md-12 form-group">
        {!! Form::label('floatingSelectGrid', 'Project Description for ALL', ['class' => 'col-form-label']) !!}
        {!! Form::textArea('description_for_all', null, ['class' => 'form-control init-ck-editor', 'id'=> "description_for_all",
        'placeholder'=> "Enter Project Description For All" , 'rows' => 5])
        !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('allocation_id', 'Allocation', ['class' => 'col-form-label']) !!}
        {!! Form::select('allocation_id',$allocations,null, ['class' => 'form-control select2', 'id' =>
        'allocation_id']) !!}
    </div>

    <div class="col-md-6 form-group">
        {!! Form::label('team_lead_id', 'Team Lead', ['class' => 'col-form-label']) !!}
        {!! Form::select('team_lead_id',$teamLeads,null, ['class' => 'form-control select2', 'id' => 'team_lead_id'])
        !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('reviewer_id', 'Reviewer', ['class' => 'col-form-label']) !!}
        {!! Form::select('reviewer_id',$reviewers,null, ['class' => 'form-control select2', 'id' => 'reviewer_id']) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('bde_id', 'BDE', ['class' => 'col-form-label']) !!}
        {!! Form::select('bde_id',$roleUsers,null, ['class' => 'form-control select2', 'id' => 'bde_id']) !!}
    </div>
    <div class="col-md-4 form-group">
        {!! Form::label('priority_id', 'Priority', ['class' => 'col-form-label']) !!}
        {!! Form::select('priority_id',$priorities,null, ['class' => 'form-control select2', 'id' => 'priority_id']) !!}
    </div>
    <div class="col-md-4 form-group">
        {!! Form::label('status_id', 'Status', ['class' => 'col-form-label']) !!}
        {!! Form::select('status',$statuses,null, ['class' => 'form-control select2', 'id' => 'status_id']) !!}
    </div>
    <div class="col-md-4 form-group">
        {!! Form::label('project_type', 'Project Type', ['class' => 'col-form-label']) !!}
        {!! Form::select('project_type',$project_types,(!empty($project)) ? $project->project_type : 1, ['class' => 'form-control select2', 'id' => 'project_type']) !!}
    </div>

    <div class="col-md-12 form-group">
        {!! Form::label('technologies_ids', 'Technology', ['class' => 'col-form-label']) !!}
        {!! Form::select('technologies_ids[]',$technologies,isset($selectedTechnology) ? $selectedTechnology : null,
        ['multiple'=>'multiple','class' => 'form-control select2', 'id' => 'technologies_ids']) !!}
    </div>
    
    @if(isset($project))
    <div class="col-md-6 form-group">
        {!! Form::label('created_at', 'Created Date', ['class' => 'col-form-label']) !!}
        {!! Form::text(null, $project->date, ['class' => 'form-control', 'id'=> "created_at", 'readonly' => true]) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('created_by', 'Created By', ['class' => 'col-form-label']) !!}
        {!! Form::text(null, $project->created_by_name, ['class' => 'form-control', 'id'=> "created_by", 'readonly' =>
        true]) !!}
    </div>
    @endif
    <div class="col-md-12 form-group mt-3 d-flex justify-content-center">
        <a href="{{url()->previous()}}" type="reset" class="btn btn-secondary mx-2 w-md">Cancel</a>
        <button type="submit" class="btn btn-primary w-md">Save</button>
    </div>
</div>
