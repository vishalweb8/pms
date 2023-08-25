<div class="modal-body"> 
    <div class="form-group mb-3">
        {!! Form::label('project_id', 'Select Project', ['class' => 'col-form-label pt-0'])
        !!}
        {!! Form::select('project_id', $projects, null, ['class' => 'form-control
         form-group select2', 'id'=> "project_id",]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('month', 'Select Month', ['class' => 'col-form-label pt-0'])
        !!}
        {!! Form::select('month', getMonths(), $expectedRevenue->month ?? date('n'), ['class' => 'form-control
         form-group select2']) !!}
    </div>
    <div class="form-group mb-3">
        @php
            $years = [];
            for($year = Carbon\Carbon::now()->format('Y')+1; $year >= Config::get('constant.start_year'); $year--) {
                $years[$year] = $year;
            }
        @endphp
        {!! Form::label('year', 'Select Year', ['class' => 'col-form-label pt-0'])
        !!}
        {!! Form::select('year', $years, $expectedRevenue->year ?? date('Y'), ['class' => 'form-control
         form-group select2']) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('actual_revenue', 'Actual Revenue($)', ['class' => 'col-form-label pt-0']) !!}
        {!! Form::text('actual_revenue', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "actual_revenue", 'placeholder'=> "Enter Actual Revenue"]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('expected_revenue', 'Expected Revenue($)', ['class' => 'col-form-label pt-0']) !!}
        {!! Form::text('expected_revenue', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "expected_revenue", 'placeholder'=> "Enter Expected Revenue"]) !!}
    </div>
</div>
