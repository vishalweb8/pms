<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Team name', ['class' => 'col-form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "name", 'placeholder'=> "Enter Team Name"]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::Label('floatingSelectGrid', 'Team Leader', ['class' => 'col-form-label']) !!}
        {!! Form::select('team_lead_id[]',$teamLeaders,(isset($teamLeadersSelected)) ? $teamLeadersSelected : null, ['class' => 'form-control select2' , 'id' => 'teams_select','multiple' => 'multiple']) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="status-teams">Status</label>
            <input class="form-check-input" type="checkbox" name="status-teams" id="status-teams" {{ (isset($team) && $team->status) ? 'checked' : (isset($team) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
