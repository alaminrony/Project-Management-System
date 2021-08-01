@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bug"></i>@lang('label.BUG_REPORT')
            </div>
            <div class="pull-right" style="margin-top: 3px;">
                <a href="{{URL::to('bugReport?generate=true&fromDate='.Request::get('fromDate').'&toDate='.Request::get('toDate').'&projectId='.Request::get('projectId').'&createdBy='.Request::get('createdBy').'&view=pdf')}}"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf" ><i class="fa fa-file-pdf-o"></i></a>
                <a href="{{URL::to('bugReport?generate=true&fromDate='.Request::get('fromDate').'&toDate='.Request::get('toDate').'&projectId='.Request::get('projectId').'&createdBy='.Request::get('createdBy').'&view=print')}}"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'bugReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {!! Form::hidden('generate','true') !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="userGroup">@lang('label.FROM_DATE')</label>
                        <div class="col-md-8">
                            {!! Form::text('from_date',Request::get('fromDate'),['class' => 'form-control datepicker', 'id'=>'fromDate','placeholder'=>'yyyy-mm-dd','readonly']) !!}
                            <span class="text-danger">{{$errors->first('from_date')}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="department">@lang('label.TO_DATE')</label>
                        <div class="col-md-8">
                            {!! Form::text('to_date',Request::get('toDate'),['class' => 'form-control datepicker','id'=>'toDate','placeholder'=>'yyyy-mm-dd','readonly']) !!}
                            <span class="text-danger">{{$errors->first('to_date')}}</span>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="designation">@lang('label.PROJECT')</label>
                        <div class="col-md-8">
                            {!! Form::select('project_id',$projects, Request::get('projectId'), ['class' => 'form-control js-source-states','id'=>'projectId']) !!}
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="createdBy">@lang('label.CREATED_BY')</label>
                        <div class="col-md-8">
                            {!! Form::select('created_by',$createdBy,Request::get('createdBy'),['class' => 'form-control js-source-states','id'=>'createdBy']) !!}
                            <span class="text-danger">{{$errors->first('all-empty')}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->


            @if(!empty($generate) && $generate == 'true')
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.TITLE')</th>
                            <th>@lang('label.PROJECT')</th>
                            <th>@lang('label.REPORTING_DATE')</th>
                            <th>@lang('label.REPORTING_MEDIUM')</th>
                            <th>@lang('label.REPORTED_BY')</th>
                            <th>@lang('label.CREATED_BY')</th>
                            <th>@lang('label.SEVERITY_LEVEL')</th>
                            <th>@lang('label.UNIQUE_CODE')</th>
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
                            <td>{{ $target->created_by }}</td>
                            <td>{{ $target->category_level }}</td>
                            <td>{{ $target->unique_code }}</td>
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
            @endif
        </div>	
    </div>
</div>
@stop