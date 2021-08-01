@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-file-photo-o"></i>@lang('label.USER_GROUP_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[68][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('userGroup/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_PPE')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'userGroup/filter','class' => 'form-horizontal')) !!}
                {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'User Group Name', 'placeholder' => 'User Group Name', 'list'=>'search', 'autocomplete'=>'off']) !!} 
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
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
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
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $target->name !!}</td>
                            <td class="text-center vcenter">
                                <div>
                                    @if(!empty($userAccessArr[68][3]))
                                    <a class="btn btn-icon-only btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('userGroup/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    <?php 
                                    $groupHasRoleAccess = Common::groupHasRoleAccess($target->id);
                                    ?>
                                    @if(!empty($userAccessArr[68][4]) && $groupHasRoleAccess == 1)
                                    {!! Form::open(array('url' => 'userGroup/' . $target->id.'/'.Helper::queryPageStr($qpArr))) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    <button class="btn btn-icon-only btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    {!! Form::close() !!}
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="3" class="vcenter">@lang('label.NO_USER_GROUP_FOUND')</td>
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