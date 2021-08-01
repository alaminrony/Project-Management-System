<html>
    <head>
        <title></title>
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/pdf.css')}}" rel="stylesheet" type="text/css" media="all"/>
    </head>
    <body>

        <div class="header">
            <h3 class="text-center">@lang('label.EMPLOYEE_REPORT_FOR_BUG')</h3>
        </div>

        <div class="row">
            <div class="text-center">
                <div class="col-md-3 print-4-header">
                    <label>@lang('label.FORM_DATE') {{!empty($request->fromDate) ? $request->fromDate : 'N/A'}}</label>
                </div>
                <div class="col-md-3 print-4-header">
                    <label>@lang('label.TO_DATE') {{!empty($request->toDate) ? $request->toDate : 'N/A'}}</label>
                </div>
                <div class="col-md-3 print-4-header">
                    <label>@lang('label.EMPLOYEE') {{!empty($employee[$request->employeeId]) && $request->employeeId > 0 ? $employee[$request->employeeId] : 'All'}}</label>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover">
            <thead style="background-color: #29CD98;">
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

                    <td>
                        @if($target->progress > '0')
                        {{!empty($target->progress) ? $target->progress :''}} %
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

        <div class="row">
            <div class="col-md-4">
                <p>Created By: {{Auth::user()->first_name.' '.Auth::user()->last_name}}</p>
            </div>
            <div class="col-md-8 print-footer">
                <p>Project Management System. Develop By &copy; <a href="http://alaminrony.tk/" target='_blank'>alaminrony</a></p>
            </div>
        </div>

    </body>
    <script src="{{asset('public/js/jquery.min.js')}}"></script>
    <script>
$(document).ready(function () {
    window.print();
});
    </script>

</html>





