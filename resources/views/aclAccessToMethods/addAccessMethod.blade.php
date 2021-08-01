@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.CREATE_ACCESS_TO_METHODS')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '#', 'id' => 'aclAccessToMethodsForm', 'class' => 'form-horizontal')) !!}
            <div class="form-body">
                <div class="form-group mt-repeater">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-offset-1 col-md-7">
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="aclAccessId">@lang('label.ACTIVITY') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-8">
                                        {!! Form::select('acl_access_id', $aclAccessList, null, ['class' => 'form-control js-source-states', 'id' => 'aclAccessId']) !!}
                                        <span class="text-danger">{{ $errors->first('aclAccessId') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="showMethods">

                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script>
    $(function () {
        //get access method on cahnge access id
        $("#aclAccessId").on("change", function () {
            var aclAccessId = $("#aclAccessId").val();
            $.ajax({
                url: " {{ URL::to('/aclAccessToMethods/getAccessMethod')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    acl_access_id: aclAccessId,
                },
                type: "POST",
                dataType: "json",
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showMethods').html(res.accessMethod);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        // end - get access method on cahnge access id
        
        //submit access method data
        $(document).on('click', '#aclAccessToMethodsSubmit', function (e) {
            e.preventDefault();
            var form_data = new FormData($('#aclAccessToMethodsForm')[0]);

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('/aclAccessToMethods/accessToMethodSave')}}",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            toastr.success(res, '@lang("label.METHOD_NAME_ADDED_SUCCESSFULLY")', options);
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }

            });

        });
        //end - submit access method data
    });
</script>
@stop