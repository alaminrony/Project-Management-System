@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.GANTT_CHART')
            </div>
        </div>
        <div class="portlet-body form">
            <div id="container" style="height:330px;"></div>
        </div>
    </div>
</div>

<link href="{{asset('public/css/ganttChart/anychart-ui.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('public/css/ganttChart/anychart-font.min.css')}}" rel="stylesheet" type="text/css">


<script src="{{asset('public/js/ganttChart/anychart-base.min.js')}}"></script>
<script src="{{asset('public/js/ganttChart/anychart-ui.min.js')}}"></script>
<script src="{{asset('public/js/ganttChart/anychart-exports.min.js')}}"></script>
<script src="{{asset('public/js/ganttChart/anychart-gantt.min.js')}}"></script>
<script src="{{asset('public/js/ganttChart/anychart-data-adapter.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.anychart-credits').find('a').remove();
    });
</script>
<script type="text/javascript">
            anychart.onDocumentReady(function () {
            // create data
            var data = [
                    @if ($chartData->isNotEmpty())
                    @foreach($chartData as $data)
                    @if (empty($data->prerequisite))
                    {
                    id: "{{$data->task_id}}",
                    name: "{{$data->title}}",
                    actualStart: "{{$data->start_time}}",
                    actualEnd: "{{$data->end_time}}",
                    
                    @endif
                    @endforeach
                    @endif

                    children: [
                            @if ($chartData->isNotEmpty())
                            @foreach($chartData as $data)
                            @if (!empty($data->prerequisite))
                    {
                            id: "{{$data->task_id}}",
                            name: "{{$data->title}}",
                            actualStart: "{{$data->start_time}}",
                            actualEnd: "{{$data->end_time}}",
                            connectTo: "{{$data->prerequisite}}",
                            connectorType: "finish-start",
                            progressValue: "75%"
                    },
                            @endif
                            @endforeach
                            @endif
                    ]
            }];
//        anychart.format.inputDateTimeFormat("yyyy-MM-dd");
                    // create a data tree
                    var treeData = anychart.data.tree(data, "as-tree");
                    // create a chart
                    var chart = anychart.ganttProject();
                    // set the data
                    chart.data(treeData);
                    // configure the scale
                    // set the container id
                    chart.container("container");
                    // initiate drawing the chart
                    chart.draw();
                    // fit elements to the width of the timeline
                    chart.fitAll();
            });</script>
@stop







