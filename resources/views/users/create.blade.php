@extends($theme)
@section('breadcrumbs')
    {{ Breadcrumbs::render('employee-create') }}
@endsection
@section('content')

<div class="row">
    <div class="col-lg-12">
        <x-common.heading heading="Add Employee Detail"></x-common.heading>
        <div id="user_wizard" class="user-wizard-step">
            <!-- Personal Details -->
            <h3>Personal Details</h3>
            <section>
                {!! Form::open(['route' => 'user.personal-details', 'id' => 'addPersonalDetail', 'class' => 'add-form','autocomplete' => "off"]) !!}
                @include('users.partials.personal_detail')
                <input type="hidden" value="{{ $createForm }}" name="createForm" class="createForm" />
                {!! Form::close() !!}
            </section>

            <!-- Official Details -->
            <h3>Official Details</h3>
            <section>
                {!! Form::open(['route' => 'user.official-details', 'id' => 'addOfficialDetail', 'class' => 'add-form','autocomplete' => "off"]) !!}
                @include('users.partials.official_detail')
                <input type="hidden" name="user_id" class="user_id" />
                <input type="hidden" value="{{ $createForm }}" name="createForm" class="createForm" />
                {!! Form::close() !!}
            </section>

            <!-- Bank Details -->
            <h3>Bank Details</h3>
            <section>
                {!! Form::open(['route' => 'user.bank-details', 'id' => 'addBankDetail', 'class' => 'add-form','autocomplete' => "off"]) !!}
                <input type="hidden" name="user_id" class="user_id"/>
                <input type="hidden" value="{{ $createForm }}" name="createForm" class="createForm" />
                @include('users.partials.bank_detail')
                {!! Form::close() !!}
            </section>

            <!-- Bank Details -->
            <h3>Education Details</h3>
            <section>
                {!! Form::open(['route' => 'user.education-details', 'id' => 'addEducationDetail', 'class' => 'add-form','autocomplete' => "off"]) !!}
                <input type="hidden" name="user_id" class="user_id"/>
                <input type="hidden" value="{{ $createForm }}" name="createForm" class="createForm" />
                <input type="hidden" name="child_length_name" id="edu_child_length"/>
                @include('users.partials.education_detail')
                {!! Form::close() !!}
            </section>

            <!-- Bank Details -->
            <h3>Experience Details</h3>
            <section>
                {!! Form::open(['route' => 'user.experience-details', 'id' => 'addExperienceDetail', 'class' => 'add-form','autocomplete' => "off"]) !!}
                <input type="hidden" name="user_id" class="user_id" />
                <input type="hidden" value="{{ $createForm }}" name="createForm" class="createForm" />
                <input type="hidden" name="child_length_name" id="expr_child_length"/>
                @include('users.partials.experience_detail')
                {!! Form::close() !!}
            </section>

            <!-- Bank Details -->
            <h3>Family Details</h3>
            <section>
                {!! Form::open(['route' => 'user.family-details', 'id' => 'addFamilyDetail', 'class' => 'add-form','autocomplete' => "off"]) !!}
                <input type="hidden" name="user_id" class="user_id" />
                <input type="hidden" value="{{ $createForm }}" name="createForm" class="createForm" />
                <input type="hidden" name="child_length_name" id="family_child_length"/>
                @include('users.partials.family_detail')
                {!! Form::close() !!}
            </section>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

@endsection
@push('scripts')
<script src="{{ asset('libs/jquery.repeater/jquery.repeater.min.js')}}"></script>
<script src="{{ asset('/js/pages/form-repeater.int.js')}}"></script>

<script type="text/javascript">
    var storePersonalDetail = "{{ route('user.personal-details') }}";
    var storeOfficialDetail = "{{ route('user.official-details') }}";
    var storeEduDetail = "{{ route('user.education-details') }}";
    var storeBankDetail = "{{ route('user.bank-details') }}";
    var storeExperienceDetail = "{{ route('user.experience-details') }}";
    var storeFamilyDetail = "{{ route('user.family-details') }}";
    var userIndex = "{{ route('user.index') }}";
    var teamLeaderMembers = "{{ route('user.team-leader-members') }}";
    var isLoadedRepeaterDiv = false;
    var SometingWentWrong = "{{ __('messages.ERR_SOMETING_WENT_WRONG') }}";
</script>

<script src="{{ asset('js/modules/users.js')}}"></script>
@endpush
