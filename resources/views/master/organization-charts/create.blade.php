<div class="modal-header">
    <h5 class="modal-title" id="organizationChartModal">Add Organization Chart</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::open(['route' => 'organizationChart.store', 'id' => 'addForm', 'class' => 'add-form']) !!}
@include('master.organization-charts.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
