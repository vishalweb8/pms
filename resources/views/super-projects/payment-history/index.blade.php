<div class="tab-pane {{ (session('selectedTab') == 2) ? 'active' : '' }}" id="paymentHistory" role="tabpanel">
    <div class="page-title-box d-flex align-items-center justify-content-between">
        <h4 class="m-0 page-title">Payment Listing</h4>
        <a class="btn btn-primary waves-effect waves-light"
            href="{{ route('paymentHistory.create',$project->id) }}">Add
            Payment History</a>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body bg-body">
                    <div class="col-12 table-responsive">
                        <table id="paymentDataTable"
                            class="table nowrap table-borderless w-100 align-middle custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Invoice No.</th>
                                    <th>Client Name</th>
                                    <th>Amount</th>
                                    <th>Expected Amount (Monthly in $) </th>
                                    <th>Currency</th>
                                    <th>Due Date</th>
                                    <th>Invoice Date</th>
                                    <th>Invoice</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
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
           },
           errorPlacement: function (error, element) {
               $(element).after("<span></span>");
               error.appendTo(element.next("span"));
           },
        });

        dataTable = $('#paymentDataTable').DataTable({
            "oLanguage": {
                "sEmptyTable": "No Records Found",
            },
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{route('paymentHistory.index',$project->id)}}",
                'type': "GET",
                data: function(e) {
                    e.date = $('#selectedDate').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'table-id'
                },
                {
                    data: 'id',
                    name: 'id',
                    "visible": false
                    // orderable: false,
                },
                {
                    data: 'invoice_no',
                    name: 'invoice_no',
                },
                {
                    data: 'client.full_name',
                    name: 'client.full_name'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'expected_amount',
                    name: 'expected_amount'
                },
                {
                    data: 'currency.name',
                    name: 'currency.name'
                },
                {
                    data: 'due_date',
                    name: 'due_date'
                },
                {
                    data: 'invoice_date',
                    name: 'invoice_date'
                },
                {
                    data: 'invoice_fullurl',
                    name: 'invoice_fullurl',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'table-action'
                }
            ],
            "order": [[ 1, "desc" ]] // Order on init. # is the column, starting at 0
        });
        dataTable.columns(dataTable.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['project-task.edit']))
        dataTable.columns(dataTable.columns().count()-1).visible(true);
        @endif
   })
</script>
@endpush