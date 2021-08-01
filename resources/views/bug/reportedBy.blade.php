{!! Form::select('reported_by',$reportByList,null,['class' => 'form-control js-source-states', 'id' => 'reportedBy']) !!} 
<script>
    $(document).ready(function () {
        $('.js-source-states').select2();
    });
</script>
