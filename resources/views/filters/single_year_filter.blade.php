
    <select class="select2 form-control no-search" name="fyear" id="financialSingleYear">
        @for($i = Carbon\Carbon::now()->format('Y'); $i >= Config::get('constant.start_year'); $i--)
        <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>
