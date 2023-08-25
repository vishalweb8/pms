@extends($theme)

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class=" ">
                    <table id="datatable" class="table nowrap table-bordered nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Team</th>
                                <!-- <th>Status</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $key => $member)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $member['first_name'] }}</td>
                                <td>{{ $member['last_name'] }}</td>
                                <td>{{ $member['email'] }}</td>
                                <td>{{ $member['userRole']['role'] }}</td>
                                <td>{{ isset($member['officialUser']['userTeam']['name']) ? $member['officialUser']['userTeam']['name'] : '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection
