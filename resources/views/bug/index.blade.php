@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bug"></i>@lang('label.BUG_LEST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[84][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('bug/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_BUG')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'bug/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="bugTitle">@lang('label.TITLE')</label>
                            <div class="col-md-8">
                                {!! Form::select('title_id',$bugTitleArr, Request::get('title'), ['class' => 'form-control js-source-states','id'=>'bugTitle']) !!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="projectId">@lang('label.PROJECT')</label>
                            <div class="col-md-8">
                                {!! Form::select('project_id',$bugProjectArr, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'projectId']) !!}
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
                            <th>@lang('label.TITLE')</th>
                            <th>@lang('label.PROJECT_NAME')</th>
                            <th>@lang('label.REPORTING_DATE')</th>
                            <th>@lang('label.REPORTING_MEDIUM')</th>
                            <th>@lang('label.REPORTED_BY')</th>
                            <th>@lang('label.SEVERITY_LEVEL')</th>
                            <th>@lang('label.UNIQUE_CODE')</th>
                            <th class="td-actions text-center">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($targetArr->isNotEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td>{{ ++$sl }}</td>
                            <td>{{ $target->bug_title }}</td>
                            <td>{{ $target->project_name }}</td>
                            <td>{{ Helper::printDate($target->reporting_date) }}</td>
                            <td>{{ $target->medium_name }}</td>
                            <td>{{ $target->reported_by }}</td>
                            <td>{{ $target->category_level }}</td>
                            <td>{{ $target->unique_code }}</td>
                            <td width="10%">
                                <div class="text-center">
                                    <div class="pull-left">
                                        <a type="button" class="btn btn-xs btn-success openBugModal tooltips" data-toggle="modal" title="View Bug" data-target="#viewBugModal" data-id="{{$target->id}}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if(!empty($userAccessArr[84][3]))
                                        <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('bug/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endif
                                    </div>
                                    @if(!empty($userAccessArr[84][4]))
                                    <div>
                                        {{ Form::open(array('url' => 'bug/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
                                        {{ Form::hidden('_method', 'DELETE') }}
                                        <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {{ Form::close() }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="10">@lang('label.NO_PROJECT_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>

<!--view Email Modal -->
<div class="modal fade" id="viewBugModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="bugModalShow">
        </div>
    </div>
</div>
<!--end Email Modal -->


<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.openBugModal', function () {
            var id = $(this).attr('data-id');
            if (id != '') {
                $.ajax({
                    url: "{{route('bug.viewDetails')}}",
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
    });
</script>
@stop