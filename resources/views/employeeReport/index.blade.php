@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')


    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bug"></i>@lang('label.EMPLOYEE_REPORT')
            </div>
            <div class="pull-right" style="margin-top: 3px;">
                <a href="{{URL::to('employeeReport?generate=true&fromDate='.Request::get('fromDate').'&toDate='.Request::get('toDate').'&employeeId='.Request::get('employeeId').'&view=pdf')}}"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf" ><i class="fa fa-file-pdf-o"></i></a>
                <a href="{{URL::to('employeeReport?generate=true&fromDate='.Request::get('fromDate').'&toDate='.Request::get('toDate').'&employeeId='.Request::get('employeeId').'&view=print')}}"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'employeeReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {!! Form::hidden('generate','true') !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE'):</label>
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
                        <label class="control-label col-md-4" for="employeeId">@lang('label.EMPLOYEE'):</label>
                        <div class="col-md-8">
                            {!! Form::select('employee_id',$employee, Request::get('employeeId'), ['class' => 'form-control js-source-states','id'=>'employeeId']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form" style="margin-left: 36%">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->


            <div class="row">
                <div class="text-center">
                    <div class="col-md-3 print-4-header">
                        <label>@lang('label.FORM_DATE') {{!empty(Request::get('fromDate')) ? Request::get('fromDate') : 'N/A'}}</label>
                    </div>
                    <div class="col-md-3 print-4-header">
                        <label>@lang('label.TO_DATE') {{!empty(Request::get('toDate')) ? Request::get('toDate') : 'N/A'}}</label>
                    </div>
                    <div class="col-md-3 print-4-header">
                        <label>@lang('label.EMPLOYEE'): {{!empty($employee[Request::get('employeeId')]) && Request::get('employeeId') > 0 ? $employee[Request::get('employeeId')] : 'N/A'}}</label>
                    </div>
                </div>
            </div>


            @if(!empty($generate) && $generate == 'true')
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.BUG_TITLE')</th>
                            <th>@lang('label.PROJECT')</th>
                            <th>@lang('label.REMARKS')</th>
                            <th>@lang('label.DATE_TIME')</th>
                            <th>@lang('label.STATUS')</th>
                            <th>@lang('label.PROGRESS')</th>
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
                            <td>{{ $target->name }}</td>
                            <td>{{ $target->remarks }}</td>
                            <td>{{ Helper::formatDate($target->date_time) }}</td>
                            @if($target->status == '1')
                            <td><span class="label label-sm label-success">@lang('label.IN_PROGRESS')</span></td>
                            @elseif($target->status == '2')
                            <td><span class="label label-sm label-warning">@lang('label.HAULT')</span></td>
                            @else
                            <td><span class="label label-sm label-danger">@lang('label.CLOSED')</span></td>
                            @endif
                            <td width="15%">
                                @if($target->progress > '0')
                                <div class="progress" id="progressBackground">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:{{!empty($target->progress) ? $target->progress :''}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{!empty($target->progress) ? $target->progress :''}} %</div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="10">@lang('label.NO_DATA_FOUND')</td>
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