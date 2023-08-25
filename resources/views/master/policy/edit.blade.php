<div class="modal-header">
    <h5 class="modal-title" id="policyModal">Edit Policy</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($policy, ['route' => ['policy.update', $policy->id], 'id' => 'editForm', 'class' => 'edit-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
<input type="hidden" name="_method" value="PATCH">
<input type="hidden" name="policyId" value="{{$policy->id}}">
@include('master.policy.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
