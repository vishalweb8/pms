
<div class="modal-body">
    <div class="row row-cols-1 row-cols-md-2">
        <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'First Name', ['class' => 'col-form-label']) !!}
            {!! Form::text('first_name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "first_name", 'placeholder'=> "Enter First Name"]) !!}
        </div>
        <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Last Name',['class' => 'col-form-label']) !!}
            {!! Form::text('last_name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "last_name", 'placeholder'=> "Enter Last Name"]) !!}
        </div>
        <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Email', ['class' => 'col-form-label']) !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "email", 'placeholder'=> "Enter Email"]) !!}
        </div>
        <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Address Line', ['class' => 'col-form-label']) !!}
            {!! Form::text('address1', null, ['class' => 'form-control', 'autocomplete' => 'nope','id'=> "address1", 'placeholder'=> "Enter Address Line"]) !!}
        </div>
        {{-- <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Address Line 2', ['class' => 'col-form-label']) !!}
            {!! Form::text('address2', null, ['class' => 'form-control', 'id'=> "address2", 'placeholder'=> "Enter Address2"]) !!}
        </div> --}}
        <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Phone Number', ['class' => 'col-form-label']) !!}
            {!! Form::text('phone_number', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "phone_number", 'placeholder'=> "Enter Phone Number"]) !!}
        </div>
        <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Select Country', ['class' => 'col-form-label']) !!}
            {!! Form::select('country_id', $countries,null,['class' => 'form-control select2', 'id' => "country"]) !!}
        </div>
        <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Select State', ['class' => 'col-form-label']) !!}
            {!! Form::select('state_id', ['' => 'Select State']+$states,null,['class' => 'form-control select2', 'id' => "state"]) !!}
        </div>
        <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Select City', ['class' => 'col-form-label']) !!}
            {!! Form::select('city_id', ['' => 'Select City']+$cities,null,['class' => 'form-control select2', 'id' => "city"]) !!}
        </div>
        <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Other Details', ['class' => 'col-form-label']) !!}
            {!! Form::textarea('other_details', null, ['class' => 'form-control', 'placeholder' => 'Enter other details', 'id' => 'other_details', 'rows' => '6', 'style' => 'height: auto']) !!}
        </div>
        @if(isset($client->getProjectByClient) && $client->getProjectByClient->count() > 0)
        <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Project by Client', ['class' => 'col-form-label']) !!} <br/>
            {!! Form::label('-', isset($client->getProjectByClient) ? (implode(', ',array_column($client->getProjectByClient->toArray(),'name'))) : '-', ['class' => 'col-form-label']) !!}
        </div>
        @endif
        {{-- <div class="col form-group">
            {!! Form::label('floatingSelectGrid', 'Zipcode', ['class' => 'col-form-label']) !!}
            {!! Form::text('zipcode', null, ['class' => 'form-control', 'id'=> "zipcode", 'placeholder'=> "Enter Zipcode"]) !!}
        </div> --}}
        {{-- <div class="mb-3">
            <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                <label class="form-check-label" for="status-client">Status</label>
                <input class="form-check-input" type="checkbox" name="status-client" id="status-client" {{ (isset($client) && $client->status) ? 'checked' : '' }}>
            </div>
        </div> --}}
    </div>
</div>
