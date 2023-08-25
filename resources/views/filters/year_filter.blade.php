@php
$startYear = currentFinStartYear();
$endYear = $startYear+1;
$fYear = $startYear.'-'.$endYear;
@endphp
<select class="select2 form-control no-search" name="fyear" id="financialYear">
    @foreach(getFinancialYears() as $list)
    <option value="{{ $list }}" @if($fYear == $list) selected @endif >{{ $list }}</option>
    @endforeach
</select>
