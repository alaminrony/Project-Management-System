@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.EDIT_CONTACT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('contact.update',$target->id), 'method' => 'PATCH','class' => 'form-horizontal', 'files' => true] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="first_name">@lang('label.FIRST_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('first_name', null, ['id'=> 'firstName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="last_name">@lang('label.LAST_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('last_name', null, ['id'=> 'lastName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="occupation">@lang('label.OCCUPATION') : </label>
                            <div class="col-md-8">
                                {!! Form::select('occupation_id', $occupationList, null, ['class' => 'form-control js-source-states', 'id' => 'occupation']) !!} 
                                <span class="text-danger">{{ $errors->first('occupation') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="company">@lang('label.COMPANY') : </label>
                            <div class="col-md-8">
                                {!! Form::select('company_id', $companyList, null, ['class' => 'form-control js-source-states', 'id' => 'company']) !!} 
                                <span class="text-danger">{{ $errors->first('company') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="designation">@lang('label.DESIGNATION') :</label>
                            <div class="col-md-8">
                                {!! Form::select('designation_id', $designationList, null, ['class' => 'form-control js-source-states', 'id' => 'designation']) !!} 
                                <span class="text-danger">{{ $errors->first('designation') }}</span>
                            </div>
                        </div>


                        @if(!empty($contactNoArr))
                        <?php $i = 1; ?>
                        @foreach($contactNoArr as $key => $contactNo)
                        <div class="form-group" id="removeContactRow{{$i}}">
                            <label class="control-label col-md-4" for="contact_number">
                                {!!  ($i == 1) ? __('label.CONTACT_NUMBER')  : '' !!}
                            </label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {!! Form::text('contact_number[]', $contactNo, ['id'=> 'contactNumber', 'class' => 'form-control']) !!}
                                    @if($i == 1)
                                    <span class="input-group-addon" id="btnMobileNumber">
                                        <i class="fa fa-plus font-red"></i>
                                    </span>
                                    @else
                                    <span class="input-group-addon remove-contact-number" data-id="{{$i}}">
                                        <i class="fa fa-remove font-red"></i>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <?php $i++; ?>
                        @endforeach
                        @endif
                        <div id="addContactNumber"></div>



                        @if(!empty($emailArr))
                        <?php $j = 1; ?>
                        @foreach($emailArr as $key => $email)
                        <div class="form-group" id="removeEmailRow{{$j}}">
                            <label class="control-label col-md-4" for="email">
                                {!!  ($j == 1) ? __('label.EMAIL') : '' !!}
                            </label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {!! Form::text('email[]',$email, ['id'=> 'email', 'class' => 'form-control']) !!}
                                    @if($j == 1)
                                    <span class="input-group-addon" id="btnEmail">
                                        <i class="fa fa-plus font-red"></i>
                                    </span>
                                    @else
                                    <span class="input-group-addon remove-email-address" data-id="{{$j}}">
                                        <i class="fa fa-remove font-red"></i>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <?php $j++; ?>
                        @endforeach
                        @endif
                        <div id="addEmail"></div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="image">@lang('label.IMAGE') :</label>
                            <div class="col-md-8">
                                {!! Form::file('image', ['id'=> 'image', 'class' => 'form-control']) !!} 
                                <img src="{{asset('public/image/'.$target->image)}}" height="100px" width="100px" alt="{{$target->first_name}} image"/>
                                <span class="text-danger">{{ $errors->first('image') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], null, ['class' => 'form-control', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/contact'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script>
    $(document).ready(function () {
        $(document).on('click', '.remove-contact-number', function () {
            var id = $(this).attr('data-id');
            var row = $(this).parents('#removeContactRow' + id);
            if (id == 1) {
                toastr["error"]("@lang('label.YOU_ARE_NOT_ABLE_TO_DELETE_FIRST_NUMBER')");
            } else {
                row.remove();
            }
        });

        var removeContactId = 1;
        $(document).on('click', '#btnMobileNumber', function () {
            $("#addContactNumber").append('<div class="form-group" id="removeContactBox_' + removeContactId + '">\
                            <label class="control-label col-md-4" for="contact_number"></label>\
                            <div class="col-md-8">\
                                <div class="input-group">\
                                <input id="contactNumber" class="form-control" name="contact_number[]" type="text">\
                                <span class="input-group-addon remove-contact" data-id="' + removeContactId + '">\
                                    <i class="fa fa-remove font-red"></i>\
                                </span>\
                                </div>\
                            </div>\
                        </div>');
            removeContactId++;
        });
        $(document).on('click', '.remove-contact', function () {
            var deleteId = $(this).attr("data-id");
            $("#removeContactBox_" + deleteId).remove();
        });
        //add email
        var removeEmailId = 1;
        $(document).on('click', '#btnEmail', function () {
            $("#addEmail").append('<div class="form-group" id="removeEmailId_' + removeEmailId + '">\
                            <label class="control-label col-md-4" for="email"></label>\
                            <div class="col-md-8">\
                                <div class="input-group">\
                                     <input id="email" class="form-control" name="email[]" type="email">\
                                    <span class="input-group-addon remove-email" data-id="' + removeEmailId + '">\
                                        <i class="fa fa-remove font-red"></i>\
                                    </span>\
                                </div>\
                                <span class="text-danger"></span>\
                            </div>\
                        </div>');
            removeEmailId++;
        });
        $(document).on('click', '.remove-email', function () {
            var deleteEmail = $(this).attr("data-id");
            $("#removeEmailId_" + deleteEmail).remove();
        });

        $(document).on('click', '.remove-email-address', function () {
            var id = $(this).attr('data-id');
            var row = $(this).parents('#removeEmailRow' + id);
            if (id == 1) {
                toastr["error"]("@lang('label.YOU_ARE_NOT_ABLE_TO_DELETE_FIRST_EMAIL')");
            } else {
                row.remove();
            }
        });
    });

</script>

@stop