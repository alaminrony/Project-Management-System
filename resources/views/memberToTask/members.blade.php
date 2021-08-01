{!! Form::select('member_id',$memberList,'',['class' => 'form-control js-source-states', 'id' => 'memberId']) !!} 
<script>
    $(document).ready(function () {
        $('.js-source-states').select2();
    });
</script>