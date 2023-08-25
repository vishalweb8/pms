<div class="modal-header">
    <h5 class="modal-title" id="technologyModal">Edit {{$display}} Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
@if(isset($userOffice))
    {!! Form::model($userOffice,['route' => ['user.official-details'], 'id' => 'addOfficeDetail', 'class' => 'edit-form']) !!}
    {!!Form ::hidden('user_id',$userOffice->user_id,['class' => 'user_id']) !!}
    <div class="modal-body">
    @include('users.partials.official_detail')
    </div>
@elseif (isset($userBank))
    {!! Form::model($userBankDetails,['route' => ['user.bank-details'], 'id' => 'addBankDetail', 'class' => 'edit-form']) !!}
    {!!Form ::hidden('user_id',$userBankDetails->user_id,['class' => 'user_id']) !!}
    <div class="modal-body">
    @include('users.partials.bank_detail')
    </div>
@elseif (isset($userEdu))
    {!! Form::model($userEducationDetails,['route' => ['user.education-details'], 'id' => 'addEducationDetail', 'class' => 'edit-form']) !!}
    {!!Form ::hidden('user_id',$userId,['class' => 'user_id']) !!}
    <div class="modal-body">
    @include('users.partials.education_detail')
    </div>
@elseif (isset($userFamily))
    {!! Form::model($userFamilyDetails,['route' => ['user.family-details'], 'id' => 'addFamilyDetail', 'class' => 'edit-form','is-repeater' => 'true']) !!}
    {!!Form ::hidden('user_id',$userId,['class' => 'user_id']) !!}
    <div class="modal-body">
    @include('users.partials.family_detail')
    </div>
@elseif (isset($userExpr))
    {!! Form::model($userExperienceDetails,['route' => ['user.experience-details'], 'id' => 'addExperienceDetail', 'class' => 'edit-form']) !!}
    {!!Form ::hidden('user_id',$userId,['class' => 'user_id']) !!}
    <div class="modal-body">
    @include('users.partials.experience_detail')
    </div>
@elseif (isset($userPer))
    {!! Form::model($user,['route' => ['user.personal-details'], 'id' => 'addPersonalDetail', 'class' => 'edit-form']) !!}
    {{-- {!!Form ::hidden('user_id',$user->id,['class' => 'user_id']) !!} --}}
    {!!Form ::hidden('id',$user->id,['class' => 'user_id_edit']) !!}
    <div class="modal-body">
    @include('users.partials.personal_detail')
    </div>
@endif
@include('common.form-footer-buttons')
{!! Form::close() !!}
