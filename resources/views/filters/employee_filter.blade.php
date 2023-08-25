<select class="form-control select2" name="employee[]" id="employee" data-placeholder=
"Select Employees" multiple>
    @foreach($users as $k => $v)
    <option value="{{ $k }}">{{ $v }}</option>
    @endforeach
</select>
