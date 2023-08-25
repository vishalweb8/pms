<select class="form-control select2 no-search" name="userType" id="userType">
    @foreach($user_type as $k => $v)
    <option value="{{ $k }}">{{ $v }}</option>
    @endforeach
</select>
