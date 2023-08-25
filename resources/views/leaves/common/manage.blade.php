@extends($theme)

@section('content')


<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Manage Leave</h4>
            <a class="btn btn-primary waves-effect waves-light cancel-btn" href="#">Leave Adjustment</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class=" ">
                    <table id="indexDataTable" class="table nowrap table-bordered w-100 align-middle custom-table dataTable no-footer dtr-inline">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Allocated Leaves</th>
                                <th>Used Leaves</th>
                                <th>Pending Leaves</th>
                                <th>Exceeded Leaves</th>
                                <th>Carry Forward</th>
                                <th>Compensate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Admin Admin</td>
                                <td>17</td>
                                <td>15</td>
                                <td>02</td>
                                <td>00</td>
                                <td>00</td>
                                <td>00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection