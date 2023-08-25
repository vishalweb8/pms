
<div class="modal-header">
    <h5 class="modal-title" id="AllocationTitle">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    {!! Form::model($activity, ['route' => ['super-admin-update-activity', ['project_id' => $project_id, 'id' => $activity->id]], 'id' => 'editForm', 'class' => 'edit-form']) !!}
    {!! Form::textarea('comments', null, ['class' => 'comment-box p-2 form-control', 'rows' => 3, 'placeholder' => "Enter Message..."]) !!}
    <div class="d-flex align-items-end justify-content-end mt-2">
        <button type="submit" class="btn-rounded chat-send w-md btn btn-primary ms-2">
            <span class="d-none d-sm-inline-block me-2">Update</span>
            <i class="mdi mdi-send"></i>
        </button>
    </div>
</div>
{!! Form::close() !!}
