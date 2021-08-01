<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-eye"></i> 
            @if($indexNumber == '0')
            {!!__('label.VIEW_OPEN_STATUS_BUG')!!}
            @elseif($indexNumber == '1')
            {!!__('label.VIEW_IN_PROGRESS_STATUS_BUG')!!}
            @elseif($indexNumber == '2')
            {!!__('label.VIEW_HAULT_STATUS_BUG')!!}
            @elseif($indexNumber == '3')
            {!!__('label.VIEW_CLOSED_STATUS_BUG')!!}
            @endif
        </h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">

            <thead>
                <tr>
                    <th><b>@lang('label.SL')</b></th>
                    <th><b>@lang('label.TITLE')</b></th>
                    <th><b>@lang('label.PROJECT_NAME')</b></th>
                    <th><b>@lang('label.REPORTING_DATE')</b></th>
                    <th><b>@lang('label.REPORTING_MEDIUM')</b></th>
                    <th><b>@lang('label.REPORTED_BY')</b></th>
                    <th><b>@lang('label.SEVERITY_LEVEL')</b></th>
                    <th><b>@lang('label.UNIQUE_CODE')</b></th>
                    <th><b>@lang('label.ATTACHMENT')</b></th>
                </tr>
            </thead>

            <tbody>
                @if($targetArr->isNotEmpty())
                <?php  $sl = 0;?>
                @foreach($targetArr as $target)
                <?php $sl++; ?>
                <tr>
                    <td>{{ $sl}}</td>
                    <td>{{ $target->bug_title }}</td>
                    <td>{{ $target->project_name }}</td>
                    <td>{{ Helper::printDate($target->reporting_date) }}</td>
                    <td>{{ $target->medium_name }}</td>
                    <td>{{ $target->reported_by }}</td>
                    <td>{{ $target->category_level }}</td>
                    <td>{{ $target->unique_code }}</td>
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
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>



