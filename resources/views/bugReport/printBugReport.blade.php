<html>
    <head>
        <title></title>
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/pdf.css')}}" rel="stylesheet" type="text/css" media="all"/>
    </head>
    <body>

        <div class="header">
            <h3 class="text-center">Bug List</h3>
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
                    <label>@lang('label.PROJECT_NAME') {{!empty($projects[$request->projectId]) && $request->projectId > 0 ? $projects[$request->projectId] : 'All'}}</label>
                </div>
                <div class="col-md-3 print-4-header">
                    <label>@lang('label.CREATED_BY') {{!empty($createdBy[$request->createdBy]) && $request->createdBy > 0 ? $createdBy[$request->createdBy] : 'All'}}</label>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover">
            <thead style="background-color: #29CD98;">
                <tr class="center">
                    <th>@lang('label.SL_NO')</th>
                    <th>@lang('label.TITLE')</th>
                    <th>@lang('label.PROJECT_NAME')</th>
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

        <div class="row">
            <div class="col-md-4">
                <p>Created By: {{Auth::user()->first_name}} {{Auth::user()->last_name}}</p>
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





