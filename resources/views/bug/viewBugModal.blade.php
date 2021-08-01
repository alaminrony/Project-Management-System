<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-eye"></i> {!!__('label.VIEW_BUG_DETAILS')!!}</h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">

            <tr>
                <td><b>@lang('label.TITLE')</b></td>
                <td>{{ $target->bug_title }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.PROJECT_NAME')</b></td>
                <td>{{ $target->project_name }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.REPORTING_DATE')</b></td>
                <td>{{ Helper::printDate($target->reporting_date) }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.REPORTING_MEDIUM')</b></td>
                <td>{{ $target->medium_name }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.REPORTED_BY')</b></td>
                <td>{{ $target->reported_by }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.SEVERITY_LEVEL')</b></td>
                <td>{{ $target->category_level }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.UNIQUE_CODE')</b></td>
                <td>{{ $target->unique_code }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.ATTACHMENT')</b></td>
                <td>
                    <?php
                    $bugFilesArr = json_decode($target->files);
                    $i = 1;
                    if (!empty($bugFilesArr)) {
                        foreach ($bugFilesArr as $bugFiles) {
                            $ext = pathinfo($bugFiles->file, PATHINFO_EXTENSION);
                            ?>
                            <div id="bug-file-show">
                                @if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg')
                                <div><a href="{{asset('public/uploads/bug/'.$bugFiles->file)}}" class="tooltips" title="{{$bugFiles->title}}" download><img src="{{asset('public/uploads/bug/'.$bugFiles->file)}}" height="50px" width="50px"></a></div>
                                @elseif($ext == 'pdf')
                                <div><a href="{{asset('public/uploads/bug/'.$bugFiles->file)}}" class="tooltips" title="{{$bugFiles->title}}" download><img src="{{asset('public/image/fileIcon/pdf.png')}}" height="50px" width="50px"></a></div>
                                @elseif($ext == 'doc')
                                <div><a href="{{asset('public/uploads/bug/'.$bugFiles->file)}}" class="tooltips" title="{{$bugFiles->title}}" download><img src="{{asset('public/image/fileIcon/doc.jpg')}}" height="50px" width="50px"></a></div>
                                @elseif($ext == 'docx')
                                <div><a href="{{asset('public/uploads/bug/'.$bugFiles->file)}}"  class="tooltips" title="{{$bugFiles->title}}" download><img src="{{asset('public/image/fileIcon/docx.jpg')}}" height="50px" width="50px"></a></div>
                                @endif
                            </div>

                            <?php
                        }
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>


