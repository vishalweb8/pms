<div class="file-input-wrapper file-input-error">
    <div {{ $attributes->merge(['class' => 'csv-import-outer']) }}>
        <div class="upload-btn-wrapper">
            <div class="icon">
                <i class="bx bxs-cloud-upload"></i>
            </div>
            <h4 class="title">Drop files here or click to upload.</h4>
            <input
                type="file"
                accept="{{ $fileType ? $fileType : '.jpg, .jpeg, .png, .jfif' }}"
                name="file_url"
                class="fileUrl"
                id="formFile"
                @if($preview)
                    onchange="displayPreview(this)"
                @endif
            />
            @if($allowedFiles)
                <div class="allowed-files text-muted">
                    {{ $slot }}
                    <span>Allowed only {{ $fileType ? $fileType : '.jpg, .jpeg, .png, .jfif' }} file(s) {{ $fileSize ? ($fileSize == 'false' ? '' : '(maximum size: '.$fileSize. ')') : '(maximum size: '. config('constant.maxFileSize').')'}}</span>
                </div>
            @endif
        </div>
    </div>
    <div class="choosen-file my-2">Drop or choose files </div>
</div>
