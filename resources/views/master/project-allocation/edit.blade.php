<div class="modal-header">
    <h5 class="modal-title" id="AllocationTitle">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($allocation, ['route' => ['allocation.update', $allocation->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('master.project-allocation.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
