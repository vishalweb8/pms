<div class="row">
    {!! Form::hidden('project_id', $project->id) !!}
    {!! Form::hidden('client_id', $project->client_id) !!}
    {!! Form::hidden('amount_in_doller', $project->client_id) !!}
    <div class="col-md-6 form-group">
        {!! Form::label('floatingSelectGrid', 'Project Name', ['class' => 'col-form-label']) !!}
        {!! Form::text(null, $project->name, ['class' => 'form-control', 'readonly' => true]) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('client_id', 'Client', ['class' => 'col-form-label']) !!}
        {!! Form::select(null,$clients,$project->client_id, ['class' => 'form-control select2', 'disabled' => true ]) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('invoice_date', 'Invoice Date', ['class' => 'col-form-label']) !!}
        <div class="input-group">
            {!! Form::text('invoice_date', null, ['class' => 'form-control date-picker', 'placeholder' => 'Select Invoice Date',  'data-date-format' => "Y-m-d", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"])
            !!}
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
        @if($errors->has('invoice_date'))
            <span class="error">{{ $errors->first('invoice_date') }}</span>
        @endif
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('invoice_no', 'Invoice No.', ['class' => 'col-form-label']) !!}
        {!! Form::text('invoice_no', null, ['class' => 'form-control', 'id'=> "invoice_no", 'placeholder'=> "Enter Invoice Number"]) !!}
        @if($errors->has('invoice_no'))
            <span class="error">{{ $errors->first('invoice_no') }}</span>
        @endif
    </div>

    <div class="col-md-6 form-group">
        {!! Form::label('amount', 'Amount', ['class' => 'col-form-label']) !!}
        {!! Form::text('amount', null, ['class' => 'form-control', 'id'=> "amount", 'placeholder'=> "Enter Amount"]) !!}
        @if($errors->has('amount'))
            <span class="error">{{ $errors->first('amount') }}</span>
        @endif
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('currency', 'Currency', ['class' => 'col-form-label']) !!}
        {!! Form::select('currency_id',$currencies, null, ['class' => 'form-control select2', 'id' => 'currency']) !!}
        @if($errors->has('currency_id'))
            <span class="error">{{ $errors->first('currency_id') }}</span>
        @endif
    </div>
    
    <div class="col-md-6 form-group">
        {!! Form::label('due_date', 'Due Date', ['class' => 'col-form-label']) !!}
        <div class="input-group">
            {!! Form::text('due_date', null, ['class' => 'form-control date-picker', 'placeholder' => 'Select Due Date', 'id'
            =>
            'due_date', 'data-date-format' => "Y-m-d", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"])
            !!}
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('billing_regular_date', 'Billing Regular Date', ['class' => 'col-form-label']) !!}
        <div class="input-group">
            {!! Form::text('billing_regular_date', null, ['class' => 'form-control date-picker', 'placeholder' => 'Select Billing Regular Date', 'id' =>
            'billing_regular_date', 'data-date-format' => "Y-m-d", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"])
            !!}
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('bank_details', 'Bank Details', ['class' => 'col-form-label']) !!}
        {!! Form::text('bank_details', null, ['class' => 'form-control', 'id'=> "bank_details", 'placeholder'=> "Enter Bank Details"]) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('bank_account', 'Bank Account', ['class' => 'col-form-label']) !!}
        {!! Form::select('bank_account',config('constant.bank_account'), null, ['class' => 'form-control select2', 'id' => 'bank_account']) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('payment_mode', 'Payment Mode', ['class' => 'col-form-label']) !!}
        {!! Form::select('payment_mode',config('constant.payment_mode'), null, ['class' => 'form-control select2', 'id' => 'payment_mode']) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('payment_platform', 'Payment Platform', ['class' => 'col-form-label']) !!}
        {!! Form::select('payment_platform',config('constant.payment_platform'), null, ['class' => 'form-control select2', 'id' => 'payment_platform']) !!}
    </div>
    <div class="col-md-12 form-group">
        <div class="card card-user-profile-info">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="drop-files">
                            {!! Form::label('upLoadInvoice', 'Upload Invoice', ['class' => 'col-form-label']) !!}
                            <div class="file-upload-wrapper">
                                <x-fileUpload fileType="image/*, .pdf, .doc" allowedFiles=true fileSize="10 MB">
                                    <i class="mdi mdi-file me-2"></i>
                                </x-fileUpload>
                                @if(isset($paymentHistory) && $paymentHistory->invoice_fullurl)
                                <div>
                                    <a href='{{$paymentHistory->invoice_fullurl}}' target='_blank'>View uploaded file</a>
                                </div>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('billing_address', 'Billing Address', ['class' => 'col-form-label']) !!}
        {!! Form::textArea('billing_address', null, ['class' => 'form-control init-ck-editor', 'id'=> "billing_address",
        'placeholder'=> "Enter Billing Address" ])
        !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('invoice_description', 'Invoice Description', ['class' => 'col-form-label']) !!}
        {!! Form::textArea('invoice_description', null, ['class' => 'form-control init-ck-editor', 'id'=> "invoice_description",
        'placeholder'=> "Enter Invoice Description"])
        !!}
    </div>
    
    <div class="col-md-12 form-group mt-3 d-flex justify-content-center">
        <a href="{{url()->previous()}}" type="reset" class="btn btn-secondary mx-2 w-md">Cancel</a>
        <button type="submit" class="btn btn-primary w-md">Save</button>
    </div>
</div>