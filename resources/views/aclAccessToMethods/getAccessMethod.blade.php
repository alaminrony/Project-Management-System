<div class="row">
    <div class="col-md-offset-2 col-md-7">
        <div class="form-group mt-repeater">
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-default">
                            <tr class="text-center">
                                <th class="text-center vcenter" width="90%">@lang('label.METHOD_NAME')</th>
                                <th class="text-center" width="10%">
                                    <a href="javascript:;" data-repeater-create class="btn btn-icon-only green mt-repeater-add pull-right">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div data-repeater-list="aclAccessIdArray" class="multiple-add-table">
                @if(!$aclAccessMethodData->isEmpty())
                @foreach($aclAccessMethodData as $item)
                <div data-repeater-item >
                    <div class="row mt-repeater-row">
                        <table class="table no-border">
                            <tr>
                                <td width="90%">{!! Form::text('method_name',!empty($item->method_name)?$item->method_name:null, ['class' => 'form-control','placeholder'=> __('label.METHOD_NAME')]) !!}</td>
                                <td width="10%">
                                    <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endforeach
                @else
                <div data-repeater-item >
                    <div class="row mt-repeater-row">
                        <table class="table no-border">
                            <tr>
                                <td width="90%">{!! Form::text('method_name',null, ['class' => 'form-control','placeholder'=> __('label.METHOD_NAME')]) !!}</td>
                                <td width="10%">
                                    <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-4 col-md-8">
            <button class="btn btn-circle green" type="submit" id="aclAccessToMethodsSubmit">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            <a href="{{ URL::to('/aclAccessToMethods') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
        </div>
    </div>
</div>
<script src="{{asset('public/assets/global/plugins/jquery-repeater/jquery.repeater.js')}}"></script>
<script src="{{asset('public/assets/pages/scripts/form-repeater.js')}}"></script>