<div id="bank_details">
    <div class="card card-user-profile-info">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('floatingSelectGrid', 'Personal Bank Name' , ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('personal_bank_name', null, ['class' => 'form-control', 'id'=> "per_bank_name", 'placeholder'=>
                        "Enter Personal Bank Name"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('floatingSelectGrid', 'Personal Bank IFSC Code', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('personal_bank_ifsc_code', null, ['class' => 'form-control', 'id'=> "per_bank_ifsc_code", 'placeholder'=> "Enter Personal Bank IFSC Code"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    {!! Form::label('floatingSelectGrid', 'Personal Account Number', ['class' => 'col-form-label pt-0']) !!}
                    <div class="form-group mb-3">
                        {!! Form::number('personal_account_number', null, ['class' => 'form-control', 'id'=> "per_account_number", 'placeholder'=>"Enter Personal Account Number",'min' => 0]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('floatingSelectGrid', 'Salary Bank Name', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('salary_bank_name', null, ['class' => 'form-control', 'id'=> "salary_bank_name", 'placeholder'=> "Enter Salary Bank Name"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('floatingSelectGrid', 'Salary Bank IFSC Code', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('salary_bank_ifsc_code', null, ['class' => 'form-control', 'id'=> "salary_bank_ifsc_code", 'placeholder'=>
                        "Enter Salary Bank IFSC Code"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('floatingSelectGrid', 'Salary Account Number', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::number('salary_account_number', null, ['class' => 'form-control', 'id'=> "salary_account_number", 'placeholder'=> "Enter Salary Account Number",'min' => 0]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('floatingSelectGrid', 'Pan Card Number', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('pan_number', null, ['class' => 'form-control', 'id'=> "pan_number", 'placeholder'=>"Enter Pan Card Number"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('floatingSelectGrid', 'Enter Name as per Your Aadhar card', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('aadharcard_name', null, ['class' => 'form-control', 'id'=> "aadharcard_name", 'placeholder'=> "Enter Name as per Your Aadhar card"]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('floatingSelectGrid', 'Aadhar Card Number', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::number('aadharcard_number', null, ['class' => 'form-control', 'id'=> "aadharcard_number", 'placeholder'=>
                        "Enter Aadhar Card Number"]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
