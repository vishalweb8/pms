<div class="modal-header">
    <h5 class="modal-title" id="interview-detailModal">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($interviewDetail, ['route' => ['interview-detail.update', $interviewDetail->id], 'id' => 'editForm', 'class' => 'edit-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
<input type="hidden" name="_method" value="PATCH">
<input type="hidden" name="interviewDetailId" value="{{$interviewDetail->id}}">
@include('interview.interview_detail.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
