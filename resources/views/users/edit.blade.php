@extends($theme)
@section('breadcrumbs')
    {{ Breadcrumbs::render('employee-edit') }}
@endsection
@section('content')
    <x-common.heading heading="Edit Employee Detail"></x-common.heading>
    <div id="user_wizard" class="user-wizard-step">
        <!-- Personal Details -->
        <h3>Personal Details</h3>
        <section>
            {!! Form::model($user,['route' => 'user.personal-details', 'id' => 'addPersonalDetail', 'class' => 'add-form']) !!}
            {!!Form ::hidden('id',$user->id,['class' => 'user_id_edit']) !!}
            {!!Form ::hidden('user_id',$user->id,['class' => 'user_id']) !!}
            {!!Form ::hidden('profile_image', $user->profile_image,['class' => 'profile_image']) !!}
            @include('users.partials.personal_detail')
            {!! Form::close() !!}
        </section>

        <!-- Official Details -->
        <h3>Work Details</h3>
        <section>
            {!! Form::model($userOfficialDetails,['route' => 'user.official-details', 'id' => 'addOfficialDetail', 'class' => 'add-form']) !!}
            @if($userOfficialDetails)
                {!!Form ::hidden('id',$userOfficialDetails->id) !!}
                {!!Form ::hidden('user_id',null,['class' => 'user_id']) !!}
            @endif
            @include('users.partials.official_detail')
            {!! Form::close() !!}
        </section>

        <!-- Bank Details -->
        <h3>Bank Details</h3>
        <section>
            {!! Form::model($userBankDetails,['route' => 'user.bank-details', 'id' => 'addBankDetail', 'class' => 'add-form']) !!}
            @if($userBankDetails)
                {!!Form ::hidden('id',$userBankDetails->id) !!}
            @endif
            {!!Form ::hidden('user_id',null,['class' => 'user_id']) !!}
            {{-- <input type="hidden" name="user_id" class="user_id"/> --}}

            @include('users.partials.bank_detail')
            {!! Form::close() !!}
        </section>

        <!-- Bank Details -->
        <h3>Education Details</h3>
        <section>
            {!! Form::model($userEducationDetails,['route' => 'user.education-details', 'id' => 'addEducationDetail', 'class' => 'add-form']) !!}
            {!!Form::hidden('user_id',null,['class' => 'user_id']) !!}
            @include('users.partials.education_detail')
            {!! Form::close() !!}
        </section>

        <!-- Bank Details -->
        <h3>Experience Details</h3>
        <section>
            {!! Form::model($userExperienceDetails,['route' => 'user.experience-details', 'id' => 'addExperienceDetail', 'class' => 'add-form']) !!}
            {!!Form ::hidden('user_id',null,['class' => 'user_id']) !!}
            @include('users.partials.experience_detail')
            {!! Form::close() !!}
        </section>

        <!-- Bank Details -->
        <h3>Family Details</h3>
        <section>
            {!! Form::model($userFamilyDetails,['route' => 'user.family-details', 'id' => 'addFamilyDetail', 'class' => 'add-form']) !!}
            {!!Form ::hidden('user_id',null,['class' => 'user_id']) !!}
            @include('users.partials.family_detail')
            {!! Form::close() !!}
        </section>

        {{-- <!-- Confirm Details -->
        <h3>Confirm Detail</h3>
        <section>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="mdi mdi-check-circle-outline text-success display-4"></i>
                        </div>
                        <div>
                            <h5>Confirm Detail</h5>
                            <p class="text-muted">If several languages coalesce, the grammar of the
                                resulting</p>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}
    </div>

@endsection
@push('scripts')
<script type="text/javascript">
    var storePersonalDetail = "{{ route('user.personal-details') }}";
    var storeOfficialDetail = "{{ route('user.official-details') }}";
    var storeEduDetail = "{{ route('user.education-details') }}";
    var storeBankDetail = "{{ route('user.bank-details') }}";
    var storeExperienceDetail = "{{ route('user.experience-details') }}";
    var storeFamilyDetail = "{{ route('user.family-details') }}";
    var userIndex = "{{ route('user.index') }}";
    var teamLeaderMembers = "{{ route('user.team-leader-members') }}";
    // var educationDeleteUrl = "{{route('delete-user.education_details',':id')}}";

    var isLoadedRepeaterDiv = false;
    var SometingWentWrong = "{{ __('messages.ERR_SOMETING_WENT_WRONG') }}";
</script>
<script src="{{ asset('libs/jquery.repeater/jquery.repeater.min.js')}}"></script>

<script src="{{ asset('/js/pages/form-repeater.int.js')}}"></script>
<script src="{{ asset('js/modules/users.js')}}"></script>
@endpush
