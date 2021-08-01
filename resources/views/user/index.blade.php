@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>@lang('label.USER_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[1][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('user/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_USER')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'user/filter','class' => 'form-horizontal')) !!}
                {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="userGroup">@lang('label.USER_GROUP')</label>
                        <div class="col-md-8">
                            {!! Form::select('user_group',  $groupList, Request::get('user_group'), ['class' => 'form-control js-source-states', 'list'=>'userGroup','autocomplete'=>'off']) !!}

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Username', 'placeholder' => 'Username','list'=>'userName','autocomplete'=>'off']) !!}
                            <datalist id="userName">
                                @if(!empty($nameArr))
                                @foreach($nameArr as $userName)
                                <option value="{{$userName->username}}"></option>
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="department">@lang('label.DEPARTMENT')</label>
                        <div class="col-md-8">
                            {!! Form::select('department',  $userDepartmentOption, Request::get('department'), ['class' => 'form-control js-source-states','id'=>'department']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="designation">@lang('label.DESIGNATION')</label>
                        <div class="col-md-8">
                            {!! Form::select('designation',  $designationList, Request::get('designation'), ['class' => 'form-control js-source-states','id'=>'designation']) !!}
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
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center">
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="text-center vcenter">@lang('label.EMPLOYEE_ID')</th>
                            <th class="text-center vcenter">@lang('label.PHOTO')</th>
                            <th class="vcenter">@lang('label.USER_GROUP')</th>
                            <th class="vcenter">@lang('label.DEPARTMENT')</th>
                            <th class="vcenter">@lang('label.DESIGNATION')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.USERNAME')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{{ ++$sl }}</td>
                            <td class="text-center vcenter">{{ $target->employee_id}}</td>
                            <td class="text-center vcenter">
                                <?php if (!empty($target->photo)) { ?>
                                    <img width="50" height="60" src="{{URL::to('/')}}/public/uploads/user/{{$target->photo}}" alt="{{ $target->full_name}}"/>
                                <?php } else { ?>
                                    <img width="50" height="60" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $target->full_name}}"/>
                                <?php } ?>
                            </td>
                            <td class="vcenter">{{ $target->group_name }}</td>
                            <td class="vcenter">{{ $target->department_name}}</td>
                            <td class="vcenter">{{ $target->designation_name}}</td>
                            <td class="vcenter">{{ $target->first_name.' '.$target->last_name }}</td>
                            <td class="vcenter">{{ $target->username }}</td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                <div>
                                    @if(!empty($userAccessArr[1][3]))
                                    <div class="pull-left">
                                        <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('user/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </div>
                                    @endif
                                    @if(!empty($userAccessArr[1][4]))
                                    <div class="pull-right">
                                        {{ Form::open(array('url' => 'user/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) }}
                                        {{ Form::hidden('_method', 'DELETE') }}
                                        <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
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
                            <td colspan="12" class="vcenter">@lang('label.NO_USER_FOUND')</td>
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