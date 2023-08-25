<div class="family_details">
    <div class="card card-user-profile-info">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="repeater" enctype="multipart/form-data">
                        <div data-repeater-list="family_group" id = 'family_group_div'>
                            @forelse ($userFamilyDetails as $k => $v )
                                <div data-repeater-item class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            {!! Form::label('floatingSelectGrid', 'Name', ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('name', $v->name, ['class' => 'form-control', 'placeholder'=>"Enter Name"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        {!! Form::Label('floatingSelectGrid', 'Relation', ['class' => 'col-form-label pt-0']) !!}
                                        {!! Form::select('relation', array('' => 'Select Relation') + config('constant.relations'),  $v->relation,['class' => 'form-control form-select2']) !!}
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            {!! Form::label('floatingSelectGrid', 'Occupation', ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('occupation', $v->occupation, ['class' => 'form-control', 'placeholder'=>"Enter Occupation"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-2">
                                            {!! Form::label('floatingSelectGrid', 'Contact Number', ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('contact_number', $v->contact_number, ['class' => 'form-control', 'placeholder'=> "Enter Contact Number"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-1 align-self-center">
                                        <div class="d-grid">
                                            @if($userFamilyDetails)
                                                {!!Form::hidden('id',$v->id,['class' => 'hid_user_id']) !!}
                                            @endif

                                            <a data-repeater-delete type="button" data-url="{{route('delete-user.family_details',':id')}}" class="div-close-btn mt-3"><i class="font-size-20 mdi mdi-close-circle text-danger"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div data-repeater-item class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            {!! Form::label('floatingSelectGrid', 'Name', ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder'=>"Enter Name"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        {!! Form::Label('floatingSelectGrid', 'Relation', ['class' => 'col-form-label pt-0']) !!}
                                        {!! Form::select('relation', array('' => 'Select Relation') + config('constant.relations'),  null,['class' => 'form-control form-select2']) !!}
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            {!! Form::label('floatingSelectGrid', 'Occupation', ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('occupation', null, ['class' => 'form-control', 'id'=> "v", 'placeholder'=>"Enter Occupation"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-2">
                                            {!! Form::label('floatingSelectGrid', 'Contact Number', ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('contact_number', null, ['class' => 'form-control', 'id'=> "contact_number", 'placeholder'=> "Enter Contact Number"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-1 align-self-center">
                                        <div class="d-grid">
                                            <a data-repeater-delete type="button" class="div-close-btn mt-3"><i class="font-size-20 mdi mdi-close-circle text-danger"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <input data-repeater-create type="button" class="btn btn-primary bg-primary mt-3 mt-lg-0" value="Add" id="repeater-button"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
