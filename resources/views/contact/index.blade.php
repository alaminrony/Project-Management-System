@extends('layouts.default.master')
@section('data_count')

<div class="col-md-12">
    @include('layouts.flash')
    <!-- BEGIN PROFILE SIDEBAR -->
    <div class="profile-content">
        <div class="col-md-2">
            <br>
            <!-- PORTLET MAIN -->
            <ul class="list-unstyled profile-nav">
                <li>
                    <img src="{{asset('public/image/'.$companyInfo->logo)}}" class="text-center img-responsive pic-bordered border border-default recruit-profile-photo-full"
                         alt="{{$companyInfo->name}}" style="width: 250px;height: auto;" />
                </li>                                    
            </ul>
        </div>
        <!-- START:: User Basic Info -->
        <div class="col-md-10">
            <br>
            <!--<div class="column sortable ">-->
            <div class="portlet portlet-sortable box green-color-style">
                <div class="portlet-body" style="padding: 8px !important">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0px">
                            <tr>
                                <td class="fit bold info">@lang('label.COMPANY_NAME')</td>
                                <td>{{$companyInfo->name}}</td>
                                <td class="fit bold info">@lang('label.INDUSTRY')</td>
                                <td>{{$companyInfo->industry_name}}</td>
                            </tr>

                            <tr>
                                <td class="fit bold info">@lang('label.SHORT_NAME')</td>
                                <td>{{$companyInfo->short_name}}</td>
                                <td class="fit bold info">@lang('label.COMPANY_TYPE')</td>
                                <td>{{ $companyInfo->type == 1 ? __('label.MOTHER_COMPANY') : __('label.SISTER_CONCERN') }}</td>
                            </tr>

                            <tr>
                                <td class="fit bold info">@lang('label.COMPANY_ADDRESS')</td>
                                <td>{{$companyInfo->address}}</td>
                                <td class="fit bold info">@lang('label.STATUS')</td>
                                <td>
                                    @if($companyInfo->status == '1')
                                    <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                    @else
                                    <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="fit bold info">@lang('label.CONTACT_NUMBER')</td>
                                <td>{{$companyInfo->contact_no}}</td>
                                <td class="fit bold info">@lang('label.CREATED_AT')</td>
                                <td>{{Helper::formatDate($companyInfo->created_at)}}</td>

                            </tr>

                            <tr>
                                <td class="fit bold info">@lang('label.EMAIL')</td>
                                <td>{{$companyInfo->email}}</td>
                                @if(!empty($companyArr[$companyInfo->mother_company_id]))
                                <td class="fit bold info">@lang('label.MOTHER_COMPANY_OF')</td>
                                <td>{{$companyArr[$companyInfo->mother_company_id]}}</td>
                                @endif
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
            <!--</div>-->
        </div>
    </div>
</div>


<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.CONTACT_LIST')
                of {{!empty($companyInfo->name)? $companyInfo->name : ''}}
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[8][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('company/'.$companyId.'/contact/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_CONTACT')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'company/'.$companyId.'/contact/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    {!!Form::hidden('companyId',$companyId)!!}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.NAME') :</label>
                            <div class="col-md-8">
                                {!! Form::select('name',$nameArr, Request::get('name'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status',$status,Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
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
                            <th colspan="2">@lang('label.FIRST_NAME')</th>
                            <th>@lang('label.LAST_NAME')</th>
                            <th>@lang('label.OCCUPATION')</th>
                            <th>@lang('label.COMPANY')</th>
                            <th>@lang('label.DESIGNATION')</th>
                            <th>@lang('label.CONTACT_NUMBER')</th>
                            <th>@lang('label.EMAIL')</th>
                            <th>@lang('label.PHOTO')</th>
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
                            <td colspan="2">{{ $target->first_name }}</td>
                            <td>{{ $target->last_name }} </td>
                            <td>{{ $target->occupation_name }}</td>
                            <td>{{ $target->company_name }}</td>
                            <td>{{ $target->designation_name }}</td>
                            <?php
                            $firstContact = "";
                            $noOfContact = 0;
                            if (!empty($target->contact_number)) {
                                $contactArray = explode(',', $target->contact_number);
                                $firstContact = $contactArray[0];
                                $noOfContact = sizeof($contactArray);
                            }
                            ?>
                            <td>
                                {{$firstContact}}
                                @if($noOfContact > 1)
                                <a type="button" class="openViewModal" data-toggle="modal" title="View Contact" data-target="#viewContactModal" data-id="{{$target->id}}">...</a>
                                @endif
                            </td>
                            <?php
                            $firstEmail = "";
                            $noOfEmail = 0;
                            if (!empty($target->email)) {
                                $emailArray = explode(',', $target->email);
                                $firstEmail = $emailArray[0];
                                $noOfEmail = sizeof($emailArray);
                            }
                            ?>
                            <td>
                                {{$firstEmail}}
                                @if($noOfEmail > 1)
                                <a type="button" class="openEmailModal" data-toggle="modal" title="View Email Address" data-target="#viewEmailModal" data-id="{{$target->id}}">...</a>
                                @endif
                            </td>
                            <td><img src="{{asset('public/image/'.$target->image)}}" alt="{{$target->first_name}}" width="50px" height="50px" /></td>
                            <td class="text-center">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td width="9%">
                                <div>
                                    <div class="pull-left">
                                        @if(!empty($userAccessArr[8][5]))
                                        <a class="btn btn-xs btn-warning tooltips" title="Details" href="{{ URL::to('contact/' . $target->id . '/details'.Helper::queryPageStr($qpArr)) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @endif
                                        
                                        @if(!empty($userAccessArr[8][3]))
                                        <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('contact/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endif
                                    </div>

                                    @if(!empty($userAccessArr[8][4]))
                                    <div class="pull-right">
                                        {{ Form::open(array('url' => 'company/'.$companyId.'/contact/' .$target->id.'/'.Helper::queryPageStr($qpArr))) }}
                                        {{ Form::hidden('_method', 'DELETE') }}
                                        <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {{ Form::close()}}
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12">@lang('label.NO_CONTACT_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>


<!--view contact Number Modal -->
<div class="modal fade" id="viewContactModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="viewModalShow">
        </div>
    </div>
</div>
<!--end view Modal -->

<!--view Email Modal -->
<div class="modal fade" id="viewEmailModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="emailModalShow">
        </div>
    </div>
</div>
<!--end Email Modal -->
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.openViewModal', function () {
            var id = $(this).attr('data-id');
            if (id != '') {
                $.ajax({
                    url: "{{route('contact.number')}}",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#viewModalShow').html(data.viewModal);
                    }
                });

            }
        });

        $(document).on('click', '.openEmailModal', function () {
            var id = $(this).attr('data-id');
            if (id != '') {
                $.ajax({
                    url: "{{route('contact.email')}}",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#emailModalShow').html(data.viewEmail);
                    }
                });

            }
        });
    });
</script>
@stop