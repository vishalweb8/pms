<div class="row">
    {!! Form::hidden('id', (isset($leadRepo->id)) ? $leadRepo->id : '', ['class' => 'form-control', 'id' => 'id']) !!}
    <!-- user_id_edit ignore this field that is just for the managing user location, inherited from the user.js -->
    {!! Form::hidden('user_id_edit', (isset($leadRepo->lead_owner_id)) ? $leadRepo->lead_owner_id : '1', ['class' => 'form-control user_id_edit', 'id' => 'user_id_edit']) !!}

    <?php
        $selectedLeadOwner = '';
        if(isset($leadRepo->lead_owner_id) && $leadRepo->lead_owner_id != '') {
            $selectedLeadOwner = $leadRepo->lead_owner_id;
        } elseif (old('lead_owner_id')){
            $selectedLeadOwner =old('lead_owner_id');
        } else {
            $selectedLeadOwner = Auth::user()->id;
        }

    ?>

    @if( $isSuperAdmin == 1 || !isset($leadRepo->lead_owner_id) )
        <div class="col-md-6">
            {!! Form::label('lead_owner_id', 'Lead Owner', ['class' => 'col-form-label']) !!}
            {!! Form::select('lead_owner_id', ['' => "Select Lead Owner"]+$leadOwners, $selectedLeadOwner, ['class' => 'form-control form-select2 select2', 'id'=>"lead_owner_id"]) !!}
        </div>
    @else
        {!! Form::hidden('lead_owner_id', (isset($leadRepo->lead_owner_id)) ? $leadRepo->lead_owner_id : Auth::user()->id, ['class' => 'form-control', 'id' => 'lead_owner_id']) !!}
    @endif

    <div class="col-md-6">
        {!! Form::label('lead_title', 'Lead Title', ['class' => 'col-form-label']) !!}
        {!! Form::text('lead_title', (isset($leadRepo->lead_title)) ? $leadRepo->lead_title : '', ['class' => 'form-control', 'id' => 'lead_title']) !!}
        @error('lead_title')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {!! Form::label('company_name', 'Company name', ['class' => 'col-form-label']) !!}
        {!! Form::text('company_name', (isset($leadRepo->company_name)) ? $leadRepo->company_name : '', ['class' => 'form-control', 'id' => 'company_name']) !!}
        @error('company_name')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {!! Form::label('first_name', 'First name', ['class' => 'col-form-label']) !!}
        {!! Form::text('first_name', (isset($leadRepo->first_name)) ? $leadRepo->first_name : '', ['class' => 'form-control', 'id' => 'first_name']) !!}
        @error('first_name')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>


    <div class="col-md-6">
        {!! Form::label('last_name', 'Last name', ['class' => 'col-form-label']) !!}
        {!! Form::text('last_name', (isset($leadRepo->last_name)) ? $leadRepo->last_name : '', ['class' => 'form-control', 'id' => 'last_name']) !!}
        @error('last_name')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>


    <div class="col-md-6">
        {!! Form::label('email', 'Email', ['class' => 'col-form-label']) !!}
        {!! Form::text('email', (isset($leadRepo->email)) ? $leadRepo->email : '', ['class' => 'form-control', 'id' => 'email']) !!}
        @error('email')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {!! Form::label('phone', 'Phone number', ['class' => 'col-form-label']) !!}
        {!! Form::text('phone', (isset($leadRepo->phone)) ? $leadRepo->phone : '', ['class' => 'form-control', 'id' => 'phone']) !!}
        @error('phone')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {!! Form::label('skype_id', 'Skype Id', ['class' => 'col-form-label']) !!}
        {!! Form::text('skype_id', (isset($leadRepo->skype_id)) ? $leadRepo->skype_id : '', ['class' => 'form-control', 'id' => 'skype_id']) !!}
        @error('skype_id')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>


    <div class="col-md-6">
        {!! Form::label('website', 'Website', ['class' => 'col-form-label']) !!}
        {!! Form::text('website', (isset($leadRepo->website)) ? $leadRepo->website : '', ['class' => 'form-control', 'id' => 'website']) !!}
        @error('website')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {!! Form::label('lead_industry_id', 'Industry', ['class' => 'col-form-label']) !!}
        {!! Form::select('lead_industry_id', ['' => "Select Industry"]+$industry, (isset($leadRepo->lead_industry_id)) ? $leadRepo->lead_industry_id : '', ['class' => 'form-control form-select2 select2', 'id'=>"lead_industry_id", 'data-tags' => 'true']) !!}
        @error('lead_industry_id')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {!! Form::label('lead_source_id', 'Source', ['class' => 'col-form-label']) !!}
        {!! Form::select('lead_source_id', ['' => "Select Source"]+$leadSource, (isset($leadRepo->lead_source_id)) ? $leadRepo->lead_source_id : '', ['class' => 'form-control form-select2 select2', 'id'=>"lead_source_id", 'data-tags' => 'true']) !!}
        @error('lead_industry_id')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {!! Form::label('lead_status_id', 'Status', ['class' => 'col-form-label']) !!}
        {!! Form::select('lead_status_id', ['' => "Select Status"]+$leadStatus, (isset($leadRepo->lead_status_id)) ? $leadRepo->lead_status_id : '', ['class' => 'form-control form-select2 select2', 'id'=>"lead_status_id", 'data-tags' => 'true']) !!}
        @error('lead_status_id')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        {!! Form::label('technology', 'Technology', ['class' => 'col-form-label']) !!}
        {!! Form::select('technology[]', $technology, (isset($leadRepo->technology)) ? $leadRepo->technology : '', ['class' => 'form-control form-select2 select2', 'id'=>"technology", 'multiple' => 'multiple']) !!}
        @error('technology')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {!! Form::label('description', 'Description', ['class' => 'col-form-label']) !!}
        {!! Form::textarea('description', (isset($leadRepo->description)) ? $leadRepo->description : '', ['class' => 'form-control', 'placeholder' => 'Description', 'id' =>
        'description', 'rows' => '4']) !!}
        @error('description')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {!! Form::Label('country_id', 'Country', ['class' => 'col-form-label']) !!}
        {!! Form::select('country_id', $countries , (isset($leadRepo->country_id)) ? $leadRepo->country_id : '', ['class' => 'form-control
        form-select2 select2', 'id'=> "temp_country"]) !!}
    </div>
    <div class="col-md-6">
        {!! Form::Label('state_id', 'State', ['class' => 'col-form-label']) !!}
        {!! Form::select('state_id', $states , (isset($leadRepo->state_id)) ? $leadRepo->state_id : '', ['class' => 'form-control form-select2 select2',
        'id'=> "temp_state"]) !!}
    </div>
    <div class="col-md-6">
        {!! Form::Label('city_id', 'City', ['class' => 'col-form-label']) !!}
        {!! Form::select('city_id', $cities , (isset($leadRepo->city_id)) ? $leadRepo->city_id : '', ['class' => 'form-control form-select2 select2',
        'id'=> "temp_city"]) !!}
    </div>

</div>

<div class="row">
    <div class="col-md-12 text-center">
        <a href="{{ route('lead.all') }}" class="btn btn-secondary my-3 me-3 text-white">Back</a>
        <button type="submit" id="savelead" class="btn btn-primary my-3">Save</button>
    </div>
</div>

@push('scripts')
<script src="{{ asset('/js/modules/users.js') }}"></script>
    <script type="text/javascript">
    $.validator.addMethod("regx", function(value, element, regexpr) {
        return regexpr.test(value);
    }, "Please enter a valid phone number.");

    jQuery.validator.addMethod("validate_email", function(value, element) {
        if(!value) return true;
        if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
            return true;
        } else {
            return false;
        }
    }, "Please enter a valid email.");

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z," "]+$/i.test(value);
    }, "Letters only please");

    $("#saveleads").click(function (e) {
        if (!$("#leadForm").valid()) e.PreventDefault();
    });


    $("#leadForm").validate({
        // initialize the plugin
        rules: {
            first_name: {
                required: {
                    depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                lettersonly: true
            },
            lead_title: {
                required: true,
            },
            last_name: {
                required: {
                    depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                lettersonly: true
            },
            email: {
                validate_email: true
            },
            phone: {
                number: true,
                minlength:8,
                maxlength:14
            },
            website: {
                url: true,
            },
            lead_source_id: {
                required: false
            },
            lead_owner_id: {
                required: true
            }
        },
        errorPlacement: function(error, element) {
			if(element.hasClass('form-select2') && element.next('.select2-container').length) {
				error.insertAfter(element.next('.select2-container'));
			} else {
				error.insertAfter(element);
			}
		},
        messages: {
            lead_owner_id: {
                required: "Please select lead owner"
            },
            lead_source_id: {
                required: "Please select lead source"
            },
            website: {
                url: "Please enter valid URL with HTTP/HTTPS"
            },
            last_name: {
                required: "Please enter last name"
            },
            first_name: {
                required: "Please enter first name"
            },
            lead_title: {
                required: "Please enter lead title"
            }
        }
    });

</script>

@endpush
