<div class="modal-header">
    <h5 class="modal-title" >Manage Resource Projects</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::open(['route' => 'store-resource-project', 'id' => 'addForm', 'class' => 'add-form', 'method' => 'post']) !!}
<div class="modal-body">
    <input type="hidden" name="user_id" value="{{$userId}}" id="user_id">
    <div class="form-group mb-3">
        {!! Form::label('project_id', 'Select Project', ['class' => 'col-form-label pt-0'])
        !!}
        {!! Form::select('project_id', $projects, null, ['class' => 'form-control
         form-group select2', 'id'=> "project_id",]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('status', 'Status', ['class' => 'col-form-label pt-0'])
        !!}
        {!! Form::select('status', config('constant.res_proj_status'), null, ['class' => 'form-control
         form-group select2', 'id'=> "status",]) !!}
    </div>
</div>

@include('common.form-footer-buttons')
{!! Form::close() !!}