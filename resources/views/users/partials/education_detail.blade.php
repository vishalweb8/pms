@php
@endphp
<div class="education_details">
    <div class="card card-user-profile-info">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="repeater" enctype="multipart/form-data">
                        <div data-repeater-list="education_group" id = 'education_group_div'>
                            @forelse ($userEducationDetails as $k => $v)

                                <div data-repeater-item class="row">
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            {{-- {!! Form::text('qualification', null, ['class' => 'form-control', 'id'=> "qualification", 'placeholder'=>"Enter Qualification"]) !!} --}}
                                            {!! Form::Label('qualification', 'Qualification' , ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::select('qualification', array('' => 'Select Qualification') + config('constant.qualification'),  $v->qualification,['class' => 'form-control form-select2','name' => 'qualification']) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            {!! Form::label('university_board', 'University Board' , ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('university_board', $v->university_board, ['class' => 'form-control', 'placeholder'=> "Enter University Board"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            {!! Form::label('grade', 'Grade' , ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('grade', $v->grade, ['class' => 'form-control', 'placeholder'=>"Enter Grade"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group mb-2">
                                            {!! Form::label('passing_year', 'Passing Year' , ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::number('passing_year', $v->passing_year, ['class' => 'form-control', 'placeholder'=> "Enter Passing Year",'min'=> 0]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-1 align-self-center">
                                        <div class="d-grid">
                                            @if($userEducationDetails)
                                                {!!Form::hidden('id',$v->id,['class' => 'hid_user_id']) !!}
                                            @endif
                                            <a data-repeater-delete type="button" data-url="{{route('delete-user.education_details',':id')}}" class="div-close-btn mt-3"><i class="font-size-20 mdi mdi-close-circle
 text-danger"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div data-repeater-item class="row">
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            {!! Form::Label('qualification', 'Qualification' , ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::select('qualification', array('' => 'Select Qualification') + config('constant.qualification'),  null,['class' => 'form-control form-select2','name' => 'qualification']) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            {!! Form::label('university_board', 'University Board' , ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('university_board', null, ['class' => 'form-control', 'id'=> "university_board", 'placeholder'=> "Enter University Board"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            {!! Form::label('grade', 'Grade' , ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::text('grade', null, ['class' => 'form-control', 'id'=> "grade", 'placeholder'=>"Enter Grade"]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group mb-2">
                                            {!! Form::label('passing_year', 'Passing Year' , ['class' => 'col-form-label pt-0']) !!}
                                            {!! Form::number('passing_year', null, ['class' => 'form-control passing-year', 'placeholder'=> "Enter Passing Year",'min'=> 0]) !!}
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
