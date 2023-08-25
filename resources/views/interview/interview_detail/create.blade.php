<div class="modal-header">
    <h5 class="modal-title" id="interview-detailModal">Add {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::open(['route' => 'interview-detail.store', 'id' => 'addForm', 'class' => 'add-form']) !!}
@include('interview.interview_detail.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
