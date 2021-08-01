@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.ACCESS_TO_METHODS')
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('aclAccessToMethods/addAccessMethod'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_ACCESS_TO_METHODS')
                    <i class="fa fa-plus create-new"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="text-center vcenter">@lang('label.ACTIVITY')</th>
                            <th class="text-center vcenter">@lang('label.METHOD_NAME')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{{ ++$sl }}</td>
                            <td class="text-center vcenter">{{ $target->acl_access_name }}</td>
                            <td class="text-center vcenter">{{ $target->method_name }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="3" class="text-center vcenter">@lang('label.NO_DATA_FOUND')</td>
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