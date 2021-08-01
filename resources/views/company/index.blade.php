@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.COMPANY_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[5][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('company/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_COMPANY')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'company/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                            <div class="col-md-8">
                                {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name/Short Name', 'placeholder' => 'Name / Short Name', 'list'=>'search', 'autocomplete'=>'off']) !!} 
                                <datalist id="search">
                                    @if(!empty($nameArr))
                                    @foreach($nameArr as $name)
                                    <option value="{{$name->name}}"></option>
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
                                {!! Form::select('status',  $status, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
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
                            <th>@lang('label.NAME')</th>
                            <th>@lang('label.SHORT_NAME')</th>
                            <th>@lang('label.COMPANY_ADDRESS')</th>
                            <th>@lang('label.CONTACT_NUMBER')</th>
                            <th>@lang('label.EMAIL')</th>
                            <th>@lang('label.INDUSTRY')</th>
                            <th>@lang('label.LOGO')</th>
                            <th>@lang('label.COMPANY_TYPE')</th>
                            <th class="text-center">@lang('label.ORDER')</th>
                            <th class="text-center">@lang('label.STATUS')</th>
                            <th class="td-actions text-center">@lang('label.ACTION')</th>
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
                            <td>{{ ++$sl }}</td>
                            <td>{{ $target->name }}</td>
                            <td>{{ $target->short_name }}</td>
                            <td>{{ $target->address }}</td>
                            <td>{{ $target->contact_no }}</td>
                            <td>{{ $target->email }}</td>
                            <td>{{ $target->industryName }}</td>
                            <td>
                                <img src="{{asset('public/image/'.$target->logo)}}" height="50px;" width="50px;"/>
                            </td>
                            <td>{{ $target->type == 1 ? __('label.MOTHER_COMPANY') : __('label.SISTER_CONCERN') }}</td>
                            <td class="text-center">{{ $target->order }}</td>
                            <td class="text-center">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td width="8%">
                                <div class="text-center">
                                    @if(!empty($userAccessArr[5][3]))
                                    <div class="pull-left">
                                        <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('company/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                            <i class="fa fa-edit"></i>
                                        </a> 
                                    </div>
                                    @endif
                                    @if(!empty($userAccessArr[5][4]))
                                    <div class="pull-right">
                                        {{ Form::open(array('url' => 'company/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
                                        {{ Form::hidden('_method', 'DELETE') }}
                                        <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {{ Form::close() }}
                                    </div>
                                    @endif
                                    @if(!empty($userAccessArr[5][17]))
                                    <div class="pull-left">
                                        <a class="btn btn-xs btn-warning tooltips" title="Contact Management" href="{{ URL::to('company/'.$target->id.'/contact') }}">
                                            <i class="fa fa-phone"></i>
                                        </a> 
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">@lang('label.NO_COMPANY_FOUND')</td>
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