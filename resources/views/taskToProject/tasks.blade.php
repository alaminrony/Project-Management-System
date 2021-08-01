<!--<label class="control-label col-md-4" for="project_id">@lang('label.ASSIGN_TASK') :<span class="text-danger"> *</span></label>-->
<div class="col-md-12">
    <table class="table table-bordered table-hover" id="dataTable">
        <thead>
            <tr>
                <th class="text-center vcenter">
        <div class="md-checkbox"  id="checkedTrue">
            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-check']) !!}
            <label for="checkAll">
                <span class="inc"></span>
                <span class="check mark-caheck"></span>
                <span class="box mark-caheck"></span>&nbsp;Checked all
            </label>
        </div>
        <div class="md-checkbox"  id="unchecked" style="display: none;">
            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-check']) !!}
            <label for="checkAll">
                <span class="inc"></span>
                <span class="check mark-caheck"></span>
                <span class="box mark-caheck"></span>&nbsp;Unchecked
            </label>
        </div>
        </th>
        <th width='15%'>@lang('label.TASK_TITLE')</th>
        <th width='20%'>@lang('label.START_TIME')</th>
        <th width='20%'>@lang('label.END_TIME')</th>
        <th width='30%'>@lang('label.PREREQUISITE')</th>
        </tr>
        </thead>

        <tbody>
            @if($taskArray->isNotEmpty())  
            @foreach($taskArray as $task)
            <tr>
                <td class="text-center">
                    <div class="md-checkbox has-success">
                        {!! Form::checkbox('task_id[]',$task->id,in_array($task->id,$checkProjectTask) ? true :false,['id' =>$task->id,'class'=> 'md-check bf-check']) !!}
                        <label for="{{$task->id}}">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                    </div>
                </td>
                <td >{{$task->title}}</td>
                <td>
                    {!!Form::text('start_time['.$task->id.']',!empty($taskToProjectList[$task->id]['start_time']) ? $taskToProjectList[$task->id]['start_time'] : '',['id'=> 'startTime_'.$task->id, 'class' => 'form-control datepicker'])!!}
                </td>
                <td>
                    {!!Form::text('end_time['.$task->id.']',!empty($taskToProjectList[$task->id]['end_time']) ? $taskToProjectList[$task->id]['end_time'] : '',['class' => 'form-control datepicker'])!!}
                </td>
                <td>
                    <select name="prerequisite[<?php echo $task->id ?>]" class="form-control js-source-states">
                        <option value="">--Select Task--</option> 
                        @if($taskArray->isNotEmpty())
                        @foreach($taskArray as $prerequisite)
                        @if($task->id !== $prerequisite->id)
                        <option value="{{$prerequisite->id}}" <?php  if(!empty($taskToProjectList[$task->id]['prerequisite']) && $prerequisite->id == $taskToProjectList[$task->id]['prerequisite'] ){echo 'selected="selected"';}else{echo "";}?>>{{$prerequisite->title}}</option> 
                        @endif
                        @endforeach
                        @endif
                    </select>
                </td>
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
        $(".js-source-states").select2();
        $('#checkAll').on("click", function () { //'check all' change
            if ($(this).prop('checked')) {
                $('.bf-check').prop("checked", true);
                $('#checkedTrue').hide();
                $('#unchecked').show();
                $('.all-check').prop("checked", true);
            }
            else {
                $('.bf-check').prop("checked", false);
                $('#unchecked').hide();
                $('#checkedTrue').show();
                $('.all-check').prop("checked", false);
            }
        });
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoClose: true,
            todayHighlight: true,
        });
    });
</script>