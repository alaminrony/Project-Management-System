<div class="col-md-12">
    <span class="text-danger" id="support_persons_id"></span><br/>
    <span class="text-danger" id="team_manager_id"></span><br/>
    <span class="text-danger" id="uniqueManagerError"></span>
    <table class="table table-bordered table-hover" id="dataTable">
        <thead>
            <tr>
                <th width='15%'>@lang('label.SL')</th>
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
        <th width='15%'>@lang('label.NAME')</th>
        <th width='20%'>@lang('label.ROLE')</th>
        <th width='15%'>@lang('label.MANAGER')</th>
        <th width='10%'>@lang('label.IMAGE')</th>
        </tr>
        </thead>

        <tbody>
            @if($supMembers->isNotEmpty())
            @php $i=0; @endphp
            @foreach($supMembers as $supMember)
            @php $i++; @endphp
            <tr>
                <td>{{$i}}</td>
                <td class="text-center">
                    <div class="md-checkbox has-success">
                        {!! Form::checkbox('support_persons_id[]',$supMember->id,!empty($checkedSupportMember  && in_array($supMember->id,json_decode($checkedSupportMember->support_persons_id))) ? true : false,['id' =>$supMember->id,'class'=> 'md-check bf-check']) !!}
                        <label for="{{$supMember->id}}">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                    </div>
                </td>
                <td>{{$supMember->name}}</td>
                <td>{{$supMember->role}}</td>
                <td align="center">
                    {!!Form::radio('team_manager_id',$supMember->id,!empty($checkedSupportMember && $supMember->id == $checkedSupportMember->team_manager_id) ? true : false,['id' =>'$supMember->id','class'=> 'customRadioBtn'])!!}
                </td>
                <td ><img width="50" height="60" src="{{URL::to('/')}}/public/uploads/user/{{$supMember->image}}" alt="{{ $supMember->name}}"/></td>
            </tr>
            @endforeach
            @else
            <tr>
                <td class="vcenter" colspan="6">@lang('label.MEMBER_NOT_FOUND')</td>
            </tr>
            @endif
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

    });
</script>