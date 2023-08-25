@forelse($performers as $performer)
    <div class="col-md-15 col-sm-3">
        <div class="card custom-card">
            <div class="card-team-badge">
                <div class="team">
                    @if($performer->user->profile_image && file_exists(public_path('storage/upload/user/images/'.$performer->user->profile_image)))
                    <img 
                        src="{{asset('storage/upload/user/images/'.$performer->user->profile_image)}}" />
                    @else
                    <img  src="{{url('images/inexture-logo-icon.svg')}}" />
                    @endif
                    
                </div>
                <div class="team-detail">
                    <h3>{{$performer->user->full_name}}</h3>
                    <p>{!! $performer->user->userTeam->name ?? '' !!}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-6 col-xs-12 pr3">
                    <div class="income-detail">
                        <img src="{{ asset('/images/expense.png') }}">
                        <p>${{thousandsCurrencyFormat($performer->expense)}}</p>
                        <span>Expense</span>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-12 pl3">
                    <div class="income-detail">
                        <img src="{{ asset('/images/revenue.png') }}">
                        <p>${{thousandsCurrencyFormat($performer->revenue)}}</p>
                        <span>Revenue</span>
                    </div>
                </div>
            </div>
            <div class="efficiency-rate">
                <div>
                    <h5>
                        {{ $performer->revenue ? number_format(($performer->expense/$performer->revenue)*100,2) : 0}}%
                    </h5>
                    <span>Efficiency Rate</span>
                </div>
                <div class="badge">
                    <img src="{{ asset('/images/badge-'.$loop->iteration.'.png') }}">
                </div>
            </div>
        </div>
    </div>
    @empty
    @include('common.data-not-available')
@endforelse