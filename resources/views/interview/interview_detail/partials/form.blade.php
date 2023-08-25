<div class="modal-body">
    <div class="row">
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('name', 'Name', ['class' => 'col-form-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name', 'id' => 'name']) !!}
                <span class="error">{{$errors->first('name')}}</span>
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('email', 'Email', ['class' => 'col-form-label']) !!}
                {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email','id' => 'email']) !!}
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('phone', 'Phone', ['class' => 'col-form-label']) !!}
                {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Enter Phone Number','id' => 'phone']) !!}
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('total_experience', 'Total Experience', ['class' => 'col-form-label']) !!}
                {!! Form::text('total_experience', null, ['class' => 'form-control', 'placeholder' => 'Enter Total Experience','id' => 'total_experience']) !!}
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('current_ctc', 'Current CTC', ['class' => 'col-form-label']) !!}
                {!! Form::text('current_ctc', null, ['class' => 'form-control', 'placeholder' => 'Enter Current CTC','id' => 'current_ctc']) !!}
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('expected_ctc', 'Expected CTC', ['class' => 'col-form-label']) !!}
                {!! Form::text('expected_ctc', null, ['class' => 'form-control', 'placeholder' => 'Enter Expected CTC','id' => 'expected_ctc']) !!}
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('notice_period', 'Notice Period', ['class' => 'col-form-label']) !!}
                {!! Form::text('notice_period', null, ['class' => 'form-control', 'placeholder' => 'Enter Notice Period','id' => 'notice_period']) !!}
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('current_organization', 'Current Organization', ['class' => 'col-form-label']) !!}
                {!! Form::text('current_organization', null, ['class' => 'form-control', 'placeholder' => 'Enter Current Organization', 'id' => 'current_organization']) !!}
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('reference_id', 'Reference Name', ['class' => 'col-form-label']) !!}
                {!! Form::select('reference_id',$userId, null, ['class' => 'form-control select2', 'id' => 'reference_id']) !!}
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('source_by', 'Source By', ['class' => 'col-form-label']) !!}
                {!! Form::text('source_by', null, ['class' => 'form-control', 'placeholder' => 'Enter Source By', 'id' => 'source_by']) !!}
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                {!! Form::label('location', 'Location', ['class' => 'col-form-label']) !!}
                {!! Form::text('location', null, ['class' => 'form-control', 'placeholder' => 'Enter Location', 'id' => 'location']) !!}
            </div>
            <div class="col-lg-6 col-xl-4 form-group">
                <label class="col-form-label d-block">Status</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="active">
                    <label class="form-check-label" for="active">
                        Active
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="inactive" name="status">
                    <label class="form-check-label" for="inactive">
                        Inactive
                    </label>
                </div>
            </div>
            <div class="col-lg-6 form-group">
                <label class="col-form-label"> Resume</label>
                <x-fileUpload allowedFiles=true fileType=".doc, .pdf">
                    <i class="mdi mdi-file-pdf me-2"></i>
                </x-fileUpload>
            </div>
            <div class="col-lg-6 form-group">
                {!! Form::label('remark', 'Remark', ['class' => 'col-form-label']) !!}
                {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => '8', 'placeholder' => 'Enter Remark', 'id' => 'remark']) !!}
            </div>
        </div>
    </div>
</div>
