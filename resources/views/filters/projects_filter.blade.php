<select class="form-control select2 no-search" name="project" id="project">
    @foreach($projects as $k => $v)
    <option value="{{ $k }}">{{ $v }}</option>
    @endforeach
</select>
