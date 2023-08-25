<div class="modal-header">
    <h5 class="modal-title" id="StatusTitle">Manage  {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($billableResources, ['route' => ['update-billable-resource'], 'method' => 'POST', 'id' => 'editForm', 'class' => 'edit-form', 'is-repeater' => 'true']) !!}
{!! Form::hidden('project_id', $project_id, ['class' => 'form-control user_id_edit', 'id' => 'project_id']) !!}

<div class="modal-body">
    <div class="row">
        <div class="col-md-3">
        {!! Form::label('user_id', 'Resource', ['class' => 'col-form-label']) !!}
        </div>
        <div class="col-md-3">
            {!! Form::label('payment_type_id', 'Payment Type', ['class' => 'col-form-label']) !!}
        </div>
        <div class="col-md-3">
            {!! Form::label('currency', 'Currency', ['class' => 'col-form-label']) !!}
        </div>
        <div class="col-md-3">
        {!! Form::label('amount', 'Amount', ['class' => 'col-form-label']) !!}
        </div>
    </div>
    <div class="repeater">
        <div data-repeater-list="billable-group">
            @forelse($billableResources as $billableResource)
                <div data-repeater-item class="row mb-2">
                    <div class="col-md-3 mb-3">
                        {!! Form::select('user_id', $users, $billableResource->user_id, ['class' => 'form-control select2', 'id'=>"user_id".$billableResource->user_id]) !!}
                    </div>
                    <div class="col-md-3 mb-3">
                        {!! Form::select('payment_type_id',$paymentTypes,$billableResource->payment_type_id, ['class' => 'form-control select2']) !!}
                    </div>
                    <div class="col-md-3 mb-3">
                        {!! Form::select('currency_id',$currencies, $billableResource->currency_id, ['class' => 'form-control select2']) !!}
                    </div>
                    <div class="col-md-2 mb-3">
                        {!! Form::number('amount', (isset($billableResource->amount)) ? $billableResource->amount : '', ['class' => 'form-control', 'id' => 'amount']) !!}
                    </div>
                    {{-- @if ($loop->index > 0) --}}
                        <div class="col-lg-1 align-self-center mb-3">
                            <div class="d-grid">
                            <a data-repeater-delete type="button" class="div-close-btn "><i class="font-size-20 mdi mdi-close-circle text-danger"></i></a>
                            </div>
                        </div>
                    {{-- @endif --}}
                </div>
            @empty
                <div data-repeater-item class="row mb-2">
                    <div class="col-md-3 mb-3">
                        {!! Form::select('user_id',  $users, '', ['class' => 'form-control select2', 'id'=>"user_id"]) !!}
                    </div>
                    <div class="col-md-3 mb-3">
                        {!! Form::select('payment_type_id',$paymentTypes,$project->payment_type_id ?? null, ['class' => 'form-control select2', 'id' =>'payment_type_id']) !!}
                    </div>
                    <div class="col-md-3 mb-3">
                        {!! Form::select('currency_id',$currencies, $project->currency ?? null, ['class' => 'form-control select2', 'id' => 'currency_id']) !!}
                    </div>
                    <div class="col-md-2 mb-3">
                        {!! Form::text('amount',  '', ['class' => 'form-control', 'id' => 'amount']) !!}
                    </div>
                    <div class="col-lg-1 align-self-center mb-3">
                        <div class="d-grid">
                        <a data-repeater-delete type="button" class="div-close-btn "><i class="font-size-20 mdi mdi-close-circle text-danger"></i></a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <input data-repeater-create type="button" class="btn btn-primary bg-primary mt-3" value="Add"/>
    </div>
</div>
@include('common.form-footer-buttons')
{!! Form::close() !!}
