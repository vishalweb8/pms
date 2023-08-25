<select class="form-control select2" name="team" id="team">
    @foreach($teams as $k => $v)
    <option value="{{ $k }}">{{ $v }}</option>
    @endforeach
</select>