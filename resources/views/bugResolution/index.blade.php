@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.BUG_RESOLUTION')
            </div>

        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'bugResolution/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                            <div class="col-md-8">
                                {!! Form::text('search',Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Bug Title', 'placeholder' => 'Bug Title', 'list'=>'search', 'autocomplete'=>'off']) !!} 
                                <datalist id="search">
                                    @if(!empty($nameArr))
                                    @foreach($nameArr as $name)
                                    <option value="{{$name->bug_title}}"></option>
                                    @endforeach
                                    @endif
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                            <div class="col-md-8">
                                {!! Form::select('status',$status, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> @lang('label.FILTER')
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.BUG_TITLE')</th>
                            <th>@lang('label.PROJECT')</th>
                            <th>@lang('label.UNIQUE_CODE')</th>
                            <th>@lang('label.ATTACHMENT')</th>
                            <th>@lang('label.STATUS')</th>
                            <th>@lang('label.LOCKED_BY')</th>
                            <th>@lang('label.LOCKED_AT')</th>
                            <th>@lang('label.RE-ASSIGNED_BY')</th>
                            <th>@lang('label.LOCK_STATUS')</th>
                            <th class="td-actions text-center">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($targetArr->isNotEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        $i = 0;
                        ?>

                        @foreach($targetArr as $target)
                        <?php
                        $supMemberArr = json_decode($target->support_persons_id, true);
                        ?>
                        @if(($target->team_manager_id == Auth::user()->id) || in_array(Auth::user()->id,$supMemberArr))
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $target->bug_title }}</td>
                            <td>{{ $target->projectName }}</td>
                            <td>{{ $target->unique_code }}</td>
                            <?php
                            $files = json_decode($target->files);
                            if (!empty($files)) {
                                $attachment = [];
                                foreach ($files as $key => $file) {
                                    $attachment[$key] = $file->file;
                                }
                            }
                            ?>
                            <td align="center">
                                @if(!empty($attachment))
                                @foreach($attachment as $val)
                                @if(!empty($val))
                                <span class="label label-sm label-primary"><i class="fa fa-paperclip"></i></span>
                                @break
                                @endif
                                @endforeach
                                @endif
                            </td>
                            <td class="text-center">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.OPEN')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.CLOSED')</span>
                                @endif
                            </td>
                            <td>{{ $target->lockedByName}}</td>
                            <td>{{ Helper::formatDate($target->locked_at)}}</td>
                             <td>{{ !empty($employeeArr[$target->reassigned_by]) ? $employeeArr[$target->reassigned_by] : ''}}</td>
                            <td class="text-center">
                                @if($target->status == '1')
                                <span class="label label-sm label-success"><i class="fa fa-unlock"></i></span>
                                @else
                                <span class="label label-sm label-warning"><i class="fa fa-lock"></i></span>
                                @endif
                            </td>
                            
                            <td width="12%">
                                <div class="text-center">
                                    <div class="pull-left">
                                        <a type="button" class="btn btn-xs btn-success openBugModal tooltips" data-toggle="modal" title="View Bug Details" data-target="#viewBugModal" data-id="{{$target->id}}">
                                            <i class="fa fa-bars"></i>
                                        </a>
                                        @if($target->status == '1')
                                        <button class="btn btn-xs btn-primary bug-lock tooltips" title="Lock Bug" type="button" data-id="{{$target->id}}">
                                            <i class="fa fa-lock"></i>
                                        </button>
                                        @endif

                                        @if(!empty($followUpstatusArr[$target->id]) != '3')
                                        @if(Auth::user()->id == $target->locked_by)
                                        <a type="button" class="btn btn-xs btn-primary openFollowUpModal tooltips" data-toggle="modal" title="Follow Up" data-target="#viewFollowUpModal" data-id="{{$target->id}}">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                        @endif

                                        @if(Auth::user()->id == $target->locked_by)
                                        <a type="button" class="btn btn-xs btn-success openReassignedModal tooltips" data-toggle="modal" title="Bug Reassign" data-target="#viewBugReassignedModal" data-id="{{$target->id}}">
                                            <i class="fa fa-share"></i>
                                        </a>
                                        @endif

                                        @if(Auth::user()->id == $target->locked_by && $target->status == '2')
                                        <button class="btn btn-xs btn-warning bug-unlock tooltips" title="Unlock Bug" type="button" data-id="{{$target->id}}">
                                            <i class="fa fa-key"></i>
                                        </button>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">@lang('label.NO_PROJECT_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>

<!--view Bug Details Modal -->
<div class="modal fade" id="viewBugModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="bugModalShow">
        </div>
    </div>
</div>
<!--end Details Modal -->

<!--view Bug Details Modal -->
<div class="modal fade" id="viewFollowUpModal" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="followUpModalShow">
        </div>
    </div>
</div>
<!--end Details Modal -->

<!--view Bug Reassigned Modal -->
<div class="modal fade" id="viewBugReassignedModal" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="reassignedModalShow">
        </div>
    </div>
</div>
<!--end Details Modal -->

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.bug-lock', function (e) {
            e.preventDefault();
            var bugId = $(this).attr('data-id');
            swal({
                title: "Are you sure?",
                text: "You want to lock ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Lock it!",
                closeOnConfirm: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{url('bugResolution/changeStatus')}}",
                        data: {bug_id: bugId, bug_lock: true},
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            if (data == 'success') {
                                toastr["success"]("@lang('label.BUG_LOCKED_SUCCESSFULLY')");
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '.bug-unlock', function (e) {
            e.preventDefault();
            var bugId = $(this).attr('data-id');
            swal({
                title: "Are you sure?",
                text: "You want to Unlocked ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Unlock it!",
                closeOnConfirm: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{url('bugResolution/changeStatus')}}",
                        data: {bug_id: bugId, bug_unlock: true},
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            if (data == 'success') {
                                toastr["success"]("@lang('label.BUG_UNLOCKED_SUCCESSFULLY')");
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '.openBugModal', function () {
            var id = $(this).attr('data-id');
            if (id != '') {
                $.ajax({
                    url: "{{route('bugResolution.viewDetails')}}",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#bugModalShow').html(data.viewBug);
                    }
                });

            }
        });
        //open Follow Up MOdal
        $(document).on('click', '.openFollowUpModal', function () {
            var id = $(this).attr('data-id');
            if (id != '') {
                $.ajax({
                    url: "{{route('bugFollowup.openFollowUpModal')}}",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#followUpModalShow').html(data.createFollowUp);
                        $(".js-source-states").select2({width: '100%'});
                    }
                });

            }
        });

        $(document).on('change', '#statusId', function () {
            var statusId = $(this).val();
            if (statusId == '1') {
                $('#showProgress').show();
            } else {
                $('#showProgress').hide();
            }
        });

        $(document).on('click', '#storeFollowUp', function () {
            var statusId = $('#statusId').val();
            var formData = new FormData($('#FollowUpData')[0]);
            if (statusId == '3') {
                swal({
                    title: "Are you sure?",
                    text: "You want to Closed ? you don't change status again.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, I want",
                    closeOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        if (formData != '') {
                            $.ajax({
                                url: "{{route('bugFollowup.store')}}",
                                type: "post",
                                data: formData,
                                dataType: "json",
                                cache: false,
                                processData: false,
                                contentType: false,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (data) {
                                    $("#errorStatus").text('');
                                    $("#errorRemarks").text('');
                                    $("#errorProgress").text('');
                                    if (data.errors) {
                                        $("#errorStatus").text(data.errors.status);
                                        $("#errorRemarks").text(data.errors.remarks);
                                        $("#errorProgress").text(data.errors.progress);
                                    }
                                    if (data == 'success') {
                                        toastr["success"]("@lang('label.FOLLOW_UP_CREATED_SUCCESSFULLY')");
                                        setTimeout(function () {
                                            location.reload();
                                        }, 1000);
                                        $('#viewFollowUpModal').modal('hide');
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                if (formData != '') {
                    $.ajax({
                        url: "{{route('bugFollowup.store')}}",
                        type: "post",
                        data: formData,
                        dataType: "json",
                        cache: false,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            $("#errorStatus").text('');
                            $("#errorRemarks").text('');
                            $("#errorProgress").text('');
                            if (data.errors) {
                                $("#errorStatus").text(data.errors.status);
                                $("#errorRemarks").text(data.errors.remarks);
                                $("#errorProgress").text(data.errors.progress);
                            }
                            if (data == 'success') {
                                toastr["success"]("@lang('label.FOLLOW_UP_CREATED_SUCCESSFULLY')");
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                                $('#viewFollowUpModal').modal('hide');
                            }
                        }
                    });
                }
            }
        });

        $(document).on('click', '.openReassignedModal', function () {
            var bugId = $(this).attr('data-id');
            if (bugId != '') {
                $.ajax({
                    url: "{{route('bugResolution.bugReassigned')}}",
                    type: "POST",
                    data: {bug_id: bugId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data != '') {
                            $('#reassignedModalShow').html(data.viewData);
                            $(".js-source-states").select2({width: '100%'});
                        }
                    }

                });
            }
        });

        $(document).on('change', '#memberID', function () {
            var memberId = $(this).val();
            var bugId = $('#bugId').val();
            if (memberId != '') {
                $.ajax({
                    url: "{{route('bugResolution.getMemberAndBugData')}}",
                    type: "POST",
                    data: {member_id: memberId, bug_id:bugId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data != '') {
                            $('#showMemberInfo').html(data.viewData);
                        }
                    }

                });
            } else {
                $('#showMemberInfo').html('');
            }
        });


        $(document).on('click', '#storeReassigned', function () {
            var formData = new FormData($('#reassignedFormData')[0]);
            if (formData != '') {
                swal({
                    title: "Are you sure?",
                    text: "You want to re-assigned ? you don't re-assigned again.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, I want",
                    closeOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "{{route('bugResolution.updateLockedBy')}}",
                            type: "post",
                            data: formData,
                            dataType: "json",
                            cache: false,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                $("#errorReassigned_id").text('');
                                if (data.errors) {
                                    $("#errorReassigned_id").text(data.errors.reassigned_id);
                                }
                                if (data == 'success') {
                                    toastr["success"]("@lang('label.RE-ASSIGNED_SUCCESSFULLY_DONE')");
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                    $('#viewFollowUpModal').modal('hide');
                                }
                            }
                        });

                    }
                });
            }

        });


    });

</script>
@stop