<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Policy', ['class' => 'col-form-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "policy",
        'placeholder'=> "Enter Policy Name"]) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="status-policy">Status</label>
            <input class="form-check-input" type="checkbox" name="status" id="status-policy" {{ (isset($policy) &&
                $policy->status) ? 'checked' : (isset($policy) ? '' : 'checked' ) }}>
        </div>
    </div>
    @if(!isset($policy->file_url))
    <x-fileUpload allowedFiles=true fileType=".doc, .pdf, .docx" fileSize="10 MB">
        <i class="mdi mdi-file-pdf me-2"></i>
    </x-fileUpload>
    @else
    <x-fileUpload allowedFiles=true fileType=".doc, .pdf, .docx" fileSize="10 MB">
        <i class="mdi mdi-file-pdf me-2"></i>
    </x-fileUpload>
    <div class="d-flex justify-content-between align-items-center">
        @if (!empty($policy) && !empty($policy->file_url) &&
        file_exists(public_path('storage/upload/policy/'.$policy->file_url)))
        <a href="{{ asset('storage/upload/policy/'.$policy->file_url) }}" target="_blank">{{ $policy->file_url }}</a>
        @endif
        <a href="javascript: void(0);" class="showDropzone" title="Edit"><i
                class="far fa-edit text-primary me-2"></i></a>
    </div>
    @endif
</div>
