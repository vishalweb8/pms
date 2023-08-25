<div class="experience_details">
    <div class="card card-user-profile-info">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="repeater" enctype="multipart/form-data">
                        <div data-repeater-list="experience_group" id = 'experience_group_div'>
                            @forelse ($userExperienceDetails as $k => $v)
                                <div data-repeater-item class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            {!! Form::label('floatingSelectGrid', 'Previous Company Name', ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('previous_company', $v->previous_company, ['class' => 'form-control', 'id'=> "previous_company", 'placeholder'=>"Enter Previous Company Name"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            {!! Form::label('Joined Date', 'Joined Date', ['class' => 'col-form-label pt-0']) !!}
                                            <div class="input-group">
                                                {!! Form::text('joined_date', $v->joined_date, ['class' => 'form-control ', 'placeholder' => 'Select Joined Date', 'id' =>
                                                'joined_date', 'data-date-format' => "yyyy-mm-dd", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"]) !!}
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            {!! Form::label('Released Date', 'Released Date', ['class' => 'col-form-label pt-0']) !!}
                                            <div class="input-group">
                                                {!! Form::text('released_date', $v->released_date, ['class' => 'form-control ', 'placeholder' => 'Select Released Date', 'id' =>
                                                'released_date', 'data-date-format' => "yyyy-mm-dd", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"]) !!}
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="mb-2">
                                            {!! Form::Label('floatingSelectGrid', 'Designation', ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::select('designation_id',  $userDesignations,  $v->designation_id,['class' => 'form-control form-select2','id' => 'designation']) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-1 align-self-center">
                                        <div class="d-grid">
                                            @if($userExperienceDetails)
                                                {!! Form::hidden('id',$v->id,['class' => 'hid_user_id']) !!}
                                            @endif
                                            <a data-repeater-delete type="button" data-url="{{route('delete-user.experience_details',':id')}}" class="div-close-btn mt-3"><i class="font-size-20 mdi mdi-close-circle
 text-danger"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div data-repeater-item class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            {!! Form::label('floatingSelectGrid', 'Previous Company Name', ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('previous_company', null, ['class' => 'form-control', 'id'=> "previous_company", 'placeholder'=>"Enter Previous Company Name"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            {!! Form::label('Joined Date', 'Joined Date', ['class' => 'col-form-label pt-0']) !!}
                                            <div class="input-group">
                                                {!! Form::text('joined_date', null, ['class' => 'form-control ', 'placeholder' => 'Select Joined Date', 'id' =>
                                                'joined_date', 'data-date-format' => "yyyy-mm-dd", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"]) !!}
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            {!! Form::label('Released Date', 'Released Date', ['class' => 'col-form-label pt-0']) !!}
                                            <div class="input-group">
                                                {!! Form::text('released_date', null, ['class' => 'form-control ', 'placeholder' => 'Select Released Date', 'id' =>
                                                'released_date', 'data-date-format' => "yyyy-mm-dd", 'data-provide' => 'datepicker','data-date-autoclose'=> "true"]) !!}
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="mb-2">
                                            {!! Form::Label('floatingSelectGrid', 'Designation', ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::select('designation_id',  $userDesignations,  null,['class' => 'form-control form-select2','id' => 'designation','data-minimum-results-for-search'=> 'Infinity']) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-1 align-self-center">
                                        <div class="d-grid">
                                            <a data-repeater-delete type="button" class="div-close-btn mt-3"><i class="font-size-20 mdi mdi-close-circle
 text-danger"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <input data-repeater-create type="button" class="btn btn-primary bg-primary mt-3 mt-lg-0" value="Add"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
