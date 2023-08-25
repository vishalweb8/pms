<select class="select2 form-control no-search" name="experience" id="experience">
    <option value="">All Experience </option>
    @foreach(range(0,10) as $experience)
        <option value="{{$experience.'-'.($experience+1)}}">{{$experience.'-'.($experience+1)}} Years</option>
    @endforeach
    <option value="12-100">12+ Years</option>

</select>
