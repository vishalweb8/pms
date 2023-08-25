@extends($theme)
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="m-0 page-title">{!! $role->name !!}'s Permission</h4>
        </div>
        <div class="card">
            <div class="card-body">
                {!! Form::model($role, ['route' => ['roles.permission.store', $role->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
                @include('master.role-permission.partials.form')
                @include('common.form-footer-buttons', ['close_btn' => false])
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
