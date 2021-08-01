@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.TEAM_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[78][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('projectTeam/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_TEAM')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'projectTeam/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.TEAM_MEMBER') :</label>
                            <div class="col-md-8">
                                {!! Form::select('member_id',$memberArr,Request::get('member'),['class' => 'form-control tooltips js-source-states']) !!} 
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.PROJECT') :</label>
                            <div class="col-md-8">
                                {!! Form::select('project_id',$projectArr,Request::get('project'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
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
                            <th>@lang('label.PROJECT')</th>
                            <th>@lang('label.TEAM_MEMBER')</th>
                            <th>@lang('label.ROLE')</th>
                            <th>@lang('label.ENGAGEMENT_DATE')</th>
<!--                            <th class="text-center">@lang('label.STATUS')</th>-->
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
                            <td>{{ $target->project_name }}</td>
                            <td>{{ $target->name }}</td>
                            <td>{{ $target->role_name }}</td>
                            <td>{{ Helper::printDate($target->engagement_date) }}</td>
                            <td width="10%">
                                <div class="text-center">
                                    @if(!empty($userAccessArr[78][3]))
                                    <div class="pull-left">
                                        <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('projectTeam/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                            <i class="fa fa-edit"></i>
                                        </a> 
                                    </div>
                                    @endif
                                    @if(!empty($userAccessArr[78][4]))
                                    <div>
                                        {{ Form::open(array('url' => 'projectTeam/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
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
@stop