@foreach ($freeResourceRepo as $key => $resourceData)
<tr>
    <td class="p-1 bg-white">{{ \Carbon\Carbon::parse($key)->format('d-m-Y') }}</td>
    <td>
        <div class="avtar-td w-auto d-block">
            <div class="d-flex flex-wrap">
                @foreach($resourceData as $taskData)
                    <div class="sod-eod all-emp-name-list emp-info cursor-pointer" data-id="{{$taskData->user_id}}">
                        @if (!empty($taskData->user->profile_image))
                            <img src="{{ asset('/storage/upload/user/images/'.$taskData->user->profile_image ) }}" class="rounded-circle avatar-xs" alt="" />
                        @else
                            <div class="avatar-xs">
                                <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-12">{{$taskData->user->profile_picture}}</span>
                            </div>
                        @endif
                        <div class="name-info ms-2">
                            <h5 class="font-size-14 m-0">
                                {{ $taskData->user->full_name ?? '' }}
                            </h5>
                            <span class="font-size-12 text-muted">{{ $taskData->user->officialUser->userTeam->name ?? '' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </td>
</tr>
@endforeach
