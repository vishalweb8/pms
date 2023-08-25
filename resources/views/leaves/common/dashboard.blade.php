@extends($theme)

@section('content')



<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">My Leave</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        @include('leaves.partials.user-dashboard-count')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body bg-body">
                        <div class=" ">
                            <table id="datatable" class="project-list-table table nowrap table-bordered nowrap w-100 custom-table">
                                <thead>
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total Duration</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaves as $leave)
                                        <tr>
                                            <td>{{ $leave->userLeave->first_name }} {{ $leave->userLeave->last_name }}</td>
                                            <td>{{ $leave->type }}</td>
                                            <td>{{ $leave->start_date }}</td>
                                            <td>{{ $leave->end_date }}</td>
                                            <td>{{ $leave->duration }}</td>
                                            <td><span class="badge badge-light-success">{{ $leave->status }}</span></td>
                                            <td><a href="{{ route('leave-add-view', $leave->id) }}">Edit</a> <a href="">Delete</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>


@endsection