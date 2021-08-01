@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.CREATE_CONTACT')
                of {{!empty($companyInfo->name) ? $companyInfo->name : ''}}
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'company/contact/store','class' => 'form-horizontal','files' => true)) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                          {!!Form::hidden('company_id',$companyId)!!}
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
                            <label class="control-label col-md-4" for="designation">@lang('label.DESIGNATION') :</label>
                            <div class="col-md-8">
                                {!! Form::select('designation_id', $designationList, null, ['class' => 'form-control js-source-states', 'id' => 'designation']) !!} 
                                <span class="text-danger">{{ $errors->first('designation') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="contact_number">@lang('label.CONTACT_NUMBER') : </label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {!! Form::text('contact_number[]', null, ['id'=> 'contactNumber', 'class' => 'form-control']) !!}
                                    <span class="input-group-addon" id="btnMobileNumber">
                                        <i class="fa fa-plus font-red"></i>
                                    </span>
                                </div>
                                <span class="text-danger">{{ $errors->first('contact_number') }}</span>
                            </div>
                        </div>
                        <div id="addContactNumber"></div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="email">@lang('label.EMAIL') : </label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {!! Form::email('email[]', null, ['id'=> 'email', 'class' => 'form-control']) !!}
                                    <span class="input-group-addon" id="btnEmail">
                                        <i class="fa fa-plus font-red"></i>
                                    </span>
                                </div>
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                        </div>
                        <div id="addEmail"></div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="image">@lang('label.IMAGE') :</label>
                            <div class="col-md-8">
                                {!! Form::file('image', ['id'=> 'image', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('image') }}</span>
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']) !!}
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
                        <a href="{{ URL::to('/company/'.$companyId.'/contact'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script>
    $(function () {
        //add contact number
        $(document).on('mouseenter', '.input-group-addon', function () {
            $(this).css("cursor", "pointer");
        });
        
        var removeContactId = 1;
        $(document).on('click', '#btnMobileNumber', function () {
            $("#addContactNumber").append('<div class="form-group" id="removeContactBox_' + removeContactId + '">\
                            <label class="control-label col-md-4" for="contact_number"></label>\
                            <div class="col-md-8">\
                                <div class="input-group">\
                                <input id="contactNumber" class="form-control" name="contact_number[]" type="text">\
                                <span class="input-group-addon remove-contact-number" data-id="' + removeContactId + '">\
                                    <i class="fa fa-remove font-red"></i>\
                                </span>\
                                </div>\
                            </div>\
                        </div>');
            removeContactId++;
        });
        $(document).on('click', '.remove-contact-number', function () {
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
        });//EOF-email

        //add remarks
        var removeRemarksId = 1;
        $(document).on('click', '#btnRemarks', function () {
            $("#addRemarks").append('<div class="form-group" id="removeRemarks_' + removeRemarksId + '">\
                            <label class="control-label col-md-4" for="remarks"></label>\
                            <div class="col-md-8">\
                                <div class="input-group">\
                                    <input id="remarks" class="form-control" name="remarks[]" type="text">\
                                    <span class="input-group-addon remove-remarks"  data-id="' + removeRemarksId + '">\
                                        <i class="fa fa-remove font-red"></i>\
                                    </span>\
                                </div>\
                                <span class="text-danger"></span>\
                            </div>\
                        </div>');
            removeRemarksId++;
        });
        $(document).on('click', '.remove-remarks', function () {
            var deleteRemarks = $(this).attr("data-id");
            $("#removeRemarks_" + deleteRemarks).remove();
        }); //EOF-remarks

        //add speciality
        var removeSpecialityId = 1;
        $(document).on('click', '#btnSpeciality', function () {
            $('#addSpeciality').append('<div class="form-group" id="removeSpeciality_' + removeSpecialityId + '">'+
                            '<label class="control-label col-md-4" for="speciality"></label>'+
                            '<div class="col-md-8">'+
                                '<div class="input-group">'+
                                    '<select class="form-control js-source-states" id="speciality" name="speciality[]"><option value="0">--- Select Speciality ---</option><option value="16">Team Player</option><option value="14">Hypnotizer</option></select>'+
                                    '<span class="input-group-addon" remove-speciality" data-id="' + removeSpecialityId + '" style="cursor: pointer;">'+
                                        '<i class="fa fa-plus font-red"></i>'+
                                    '</span>'+
                                '</div>'+
                                '<span class="text-danger"></span>'+
                            '</div>'+
                        '</div>');
            removeSpecialityId++;
        });
        $(document).on('click', '.remove-speciality', function () {
            var deleteSpeciality = $(this).attr("data-id");
            $("#removeSpeciality_" + deleteSpeciality).remove();
        });
        $(document).on('click', '#btnWeakness', function () {
            $("#addWeakness").prepend('<div class="form-group">\
                            <label class="control-label col-md-4" for="weakness"> </label>\
                            <div class="col-md-8">\
                                {!! Form::select('weakness[]', $weaknessList, null, ['class' => 'form - control js - source - states', 'id' => 'weakness']) !!} \
                                <span class="text-danger">{{ $errors->first('weakness') }}</span>\
                            </div>\
                        </div>');
        });
    });
</script>
@stop