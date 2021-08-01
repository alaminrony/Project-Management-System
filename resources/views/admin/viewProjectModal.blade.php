<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-eye"></i> 
            {!!__('label.VIEW_TASK_WISE_PROJECT')!!}
        </h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>@lang('label.SL_NO')</th>
                    <th>@lang('label.NAME')</th>
                    <th>@lang('label.CLIENT')</th>
                    <th>@lang('label.PROJECT_STATUS')</th>
                    <th>@lang('label.TENTATIVE_DATE')</th>
                    <th>@lang('label.DEAD_LINE')</th>
                    <th>@lang('label.ORDER')</th>
                    <th>@lang('label.IMAGE')</th>
                    <th class="text-center">@lang('label.STATUS')</th>
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
                    <td>{{ $target->name }}</td>
                    <td>{{ $target->company_name }}</td>
                    <td>{{ $target->status_name }}</td>
                    <td>{{ Helper::printDate($target->tentative_date) }}</td>
                    <td>{{ Helper::printDate($target->dead_line) }}</td>
                    <td>{{ $target->order }}</td>
                    <td>
                        <img src="{{asset('public/image/'.$target->upload_file)}}" alt="{{$target->name}} file" height="50px;" width="50px;"/>
                    </td>
                    <td class="text-center">
                        @if($target->status == '1')
                        <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                        @else
                        <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                        @endif
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
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>



