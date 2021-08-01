<label class="control-label col-md-4" for="project_id">@lang('label.ASSIGN_TASK') :<span class="text-danger"> *</span></label>
<div class="col-md-8">
    <table class="table table-bordered table-hover" id="dataTable">
        <thead>
            <tr>
                <th class="text-center vcenter">
        <div class="md-checkbox" id="checkedTrue">
            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-check']) !!}
            <label for="checkAll">
                <span class="inc"></span>
                <span class="check mark-caheck"></span>
                <span class="box mark-caheck"></span>&nbsp;Checked all
            </label>
        </div>
        <div class="md-checkbox" id="unchecked" style="display: none;">
            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-check']) !!}
            <label for="checkAll">
                <span class="inc"></span>
                <span class="check mark-caheck"></span>
                <span class="box mark-caheck"></span>&nbsp;Unchecked
            </label>
        </div>
        </th>
        <th class="vcenter">@lang('label.TASK_TITLE')</th>
        </tr>
        </thead>
        <tbody>
              @if($taskList->isNotEmpty())  
              @foreach($taskList as $task)
            <tr>
                <td class="text-center vcenter">
                    <div class="md-checkbox has-success">
                        {!! Form::checkbox('task_id[]',$task->id,!empty($memberTaskList) ? in_array($task->id,$memberTaskList): false ,['id' =>$task->id,'class'=> 'md-check bf-check']) !!}
                        <label for="{{$task->id}}">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                    </div>
                </td>
                <td class="vcenter">{{$task->title}}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td class="vcenter" colspan="4">@lang('label.SPECIALITY_NOT_FOUND')</td>
            </tr>
            @endif
            <span class="text-danger" id="errorTasks"></span>
        </tbody>
    </table>
    
</div>

<script>
    $(document).ready(function () {
        $('#checkAll').on("click", function () { //'check all' change
            if ($(this).prop('checked')) {
                $('.bf-check').prop("checked", true);
                $('#unchecked').show();
                $('#checkedTrue').hide();
                $('.all-check').prop('checked',true);
            }
            else {
                $('.bf-check').prop("checked", false);
                $('#checkedTrue').show();
                $('#unchecked').hide();
                $('.all-check').prop('checked',false);
            }
        });
    });
</script>