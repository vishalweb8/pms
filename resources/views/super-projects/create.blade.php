@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('project-superadmin-create') }}
@endsection
@section('content')
<div class="row">
    <x-common.heading heading="Add Project Details"></x-common.heading>
    <div class="col-md-12 add-project-detail">
        <div class="card project-activity_detail h-100">
            <div class="card-body">
                {!! Form::open(['route' => 'super-admin-store-project', 'id' => 'superProjectDetailEditForm', 'class' =>
                'add-form']) !!}
                @csrf
                @include('super-projects.partials.form-edit')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/modules/projects.js')}}"></script>
<script>
$(document).ready(function(){
     $("#superProjectDetailEditForm").validate({
         // initialize the plugin
        rules: {
            name: {
                required: true,
                // regex: "^[a-z A-Z 0-9_-]+$",
            },
            project_code: {
                required: true,
                remote: {
                    url: "{{ route('check-project-code') }}",
                    type: "POST",
                    data: {
                        project_code: function(){
                            return $("#project_code").val();
                        },
                        project_id: function(){
                            return $("#project_id").val();
                        }
                    }
                }
            },
            payment_type_id: {
                // required: true
            },
            amount: {
                // required: true,
                number: true,
            },
            currency: {
                // required: true,
            },
            allocation_id: {
                // required: true,
            },
            team_lead_id: {
                // required: true,
            },
            reviewer_id: {
                // required: true,
            },
            priority_id: {
                // required: true,
            },
            status_id: {
                // required: true,
            },
            technologies_ids: {
                // required: true,
            },
            client_id: {
                // required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter project name.",
                regex: "Name should be in alphabet.",
            },
            project_code: {
                required: "Please enter project code.",
                digits: "Project code should be in numeric.",
                remote: "Project Code is already been exist"
            },
            payment_type_id: {
                required: "Please select payment type.",
            },
            amount: {
                required: "Please enter amount.",
                required: "Amount should be in digits",
            },
            currency: {
                required: "Please select allocation type.",
            },
            allocation_id: {
                required: "Please select allocation type.",
            },
            team_lead_id: {
                required: "Please select team lead.",
            },
            reviewer_id: {
                required: "Please select reviewer.",
            },
            priority_id: {
                required: "Please select priority.",
            },
            status_id: {
                required: "Please select status.",
            },
            technologies_ids: {
                required: "Please select technology.",
            },
            client_id: {
                required: "Please select client.",
            },
        },
        errorPlacement: function (error, element) {
            $(element).after("<span></span>");
            error.appendTo(element.next("span"));
        },
     });
})
</script>
@endpush
