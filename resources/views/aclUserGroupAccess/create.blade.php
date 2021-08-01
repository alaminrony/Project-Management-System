@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.USER_GROUP_ACCESS_CONTROL')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '','class' => 'form-horizontal','id' => 'accessForm')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="groupId">@lang('label.SELECT_GROUP') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('group_id', $userGroupList, null, ['class' => 'form-control js-source-states', 'id' => 'groupId']) !!}
                                <span class="text-danger">{{ $errors->first('group_id') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="showGroupAccessForm"> </div>


            {!! Form::close() !!}
        </div>	
    </div>
</div>
<div class="modal container fade" id="userGroupWiseAccessList" tabindex="-1">
    <div id="showUserGroupWiseAccessList">
        <!--    ajax will be load here-->
    </div>

</div>


<script type="text/javascript">
    $(function () {


        $(document).on('change', '#groupId', function () {
            loadAccessControl();
        });

        //code for product to process submit
        $(document).on("click", "#btnSubmit", function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, save',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: false},
                    function (isConfirm) {
                        if (isConfirm) {
                            var options = {
                                closeButton: true,
                                debug: false,
                                positionClass: "toast-bottom-right",
                                onclick: null,
                            };

                            // Serialize the form data
                            var form_data = new FormData($('#accessForm')[0]);
                            $.ajax({
                                url: "{{URL::to('aclUserGroupToAccess/relateUserGroupToAccess')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                beforeSend: function () {
                                    App.blockUI({boxed: true});
                                },
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    loadAccessControl();
                                    App.unblockUI();
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    if (jqXhr.status == 400) {
                                        var errorsHtml = '';
                                        var errors = jqXhr.responseJSON.message;
                                        $.each(errors, function (key, value) {
                                            errorsHtml += '<li>' + value[0] + '</li>';
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
                        } else {
                            swal('Cancelled', '', 'error');
                        }
                    });

        });

        //load revoke access modal
        $(document).on('click', '#btnRevoke', function () {

            //var userGroupId = $(this).attr('data-id');
            var groupId = $('#groupId').val();
            $.ajax({
                url: "{{ URL::to('aclUserGroupToAccess/getUserGroupListToRevoke') }}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    group_id: groupId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $('#showUserGroupWiseAccessList').html(res.html);
                    App.unblockUI();
                }
            });
        });
        //revoke all access
        $(document).on("click", "#btnRevokeFinalize", function (e) {
            e.preventDefault();
            var groupName = $(this).data('user-group-name');
            swal({
                title: 'Are you sure you want to proceed?',
                text: "This will revoke all access of '"+groupName+"' !",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, revoke',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: false},
                    function (isConfirm) {
                        if (isConfirm) {
                            var options = {
                                closeButton: true,
                                debug: false,
                                positionClass: "toast-bottom-right",
                                onclick: null,
                            };

                            var groupId = $('#groupId').val();
                            //alert(groupId);return false;
                            $.ajax({
                                url: "{{URL::to('aclUserGroupToAccess/revokeUserGroupAccess')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything

                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    group_id: groupId
                                },
                                beforeSend: function () {
                                    App.blockUI({boxed: true});
                                },
                                success: function (res) {
                                    toastr.success(res.message, res.heading, options);
                                    $('#userGroupWiseAccessList').modal('hide');
                                    loadAccessControl();
                                   
                                    //App.unblockUI();
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    if (jqXhr.status == 400) {
                                        var errorsHtml = '';
                                        var errors = jqXhr.responseJSON.message;
                                        $.each(errors, function (key, value) {
                                            errorsHtml += '<li>' + value[0] + '</li>';
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
                        } else {
                            swal('Cancelled', '', 'error');
                        }
                    });

        });
    });


    function loadAccessControl() {
        var groupId = $('#groupId').val();
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        if (groupId == '0') {
            $('#showGroupAccessForm').html('');
            return false;
        }
        $.ajax({
            url: '{{URL::to("aclUserGroupToAccess/getAccessControl/")}}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                group_id: groupId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showGroupAccessForm').html(res.html);
                App.unblockUI();
            }

        });
    }
</script>
@stop