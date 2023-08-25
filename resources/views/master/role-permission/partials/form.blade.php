@section('breadcrumbs')
    {{ Breadcrumbs::render('roles-permission') }}
@endsection
<?php
    $permission_titles = [
        'list' => "List",
        'create' => "Create",
        'edit' => "Edit",
        'destroy' => "Delete",
        'view' => "View",
    ]
?>
<div class="row d">
    <div class="col-12">
        <div class="  mb-3">
            <table id="indexDataTable" class="table nowrap table-bordered w-100 align-middle custom-table dataTable no-footer dtr-inline">
                <thead>
                    <tr>
                        <th>Permission Name</th>
                        @foreach ($permission_titles as $item)
                            <th class="text-center" width='100'>{{ $item }}</th>
                        @endforeach
                        {{-- <th>Delete</th> --}}
                    </tr>
                </thead>
                <tbody>

                @foreach ($permissions as $module => $permission_list)
                    <tr>
                        <td>{!! Str::of($module)->replace('-', ' ')->replace('.', ' > ')->singular()->title()->replace('Bde', "BDE") !!}</td>
                        @foreach ($permission_titles as $key => $item)
                            <td class="text-center" >
                                @if (!empty($permission_list))
                                <?php
                                    $permission_name = explode(".", key($permission_list))[0];
                                    $available_permission = array_keys($permission_list);
                                    $available_permission = array_map(function ($value) {
                                        return substr($value, strpos($value, ".") + 1);
                                    }, $available_permission);
                                    $permission = $permission_name .'.'. $key;
                                ?>
                                @if (in_array($key, $available_permission))
                                    <div class="permission-switch form-check form-switch p-0 justify-content-center" dir="ltr">
                                        {{-- <label class="form-check-label pe-2" for="SwitchCheckSizesm"></label> --}}
                                        <input class="form-check-input m-0" type="checkbox" id="SwitchCheckSizesm" name="{{ $permission }}" {{ ($role->hasDirectPermission($permission)) ? 'checked' : ''}} >
                                    </div>
                                @endif

                                @endif

                            </td>
                        @endforeach
                        {{-- @forelse($permission_list as $item => $display_name)
                        <td>
                            <div class="permission-switch form-check form-switch p-0" dir="ltr">
                                <label class="form-check-label pe-2" for="SwitchCheckSizesm"></label>
                                <input class="form-check-input m-0" type="checkbox" id="SwitchCheckSizesm" name="{{ $item }}" {{ ($role->hasDirectPermission($item)) ? 'checked' : ''}}>
                            </div>
                        </td>
                        @empty
                        <td>
                        </td>
                        @endforelse --}}
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
