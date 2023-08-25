<div class="modal-header">
    <h5 class="modal-title" id="expectedRevenueModal">Add {{$module_title}}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::open(['route' => 'expectedRevenue.store', 'id' => 'addForm', 'class' => 'add-form']) !!}
@include('expected-revenue.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
