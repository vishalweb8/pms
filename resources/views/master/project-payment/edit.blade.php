<div class="modal-header">
    <h5 class="modal-title" id="typeTitle">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($project_payment, ['route' => ['project-payment.update', $project_payment->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('master.project-payment.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
