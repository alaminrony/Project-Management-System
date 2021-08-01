<?php

namespace App\Http\Controllers;

use Validator;
use App\BugFollowUp;
use App\Bug;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class BugFollowUpController extends Controller {

    private $controller = 'BugFollowUp';

    public function openFollowUpModal(Request $request) {
//      Helper::dump($request->id);
        $status = ['0' => __('label.SELECT_STATUS'), '1' => __('label.IN_PROGRESS'), '2' => __('label.HAULT'), '3' => __('label.CLOSED')];
        $createFollowUp = view('bugFollowup.createFollowup')->with(['bug_id' => $request->id, 'status' => $status])->render();
        return response()->json(['createFollowUp' => $createFollowUp]);
    }

    public function store(Request $request) {
        
        $rules =[
            'status' => 'required|not_in:0',
            'progress'=>'lte:100|gte:1',
            'remarks'=>'required',
        ];
       
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }
        $target = new BugFollowUp;
        $target->bug_id = $request->bug_id;
        $target->date_time = date('Y-m-d H:i:s');
        $target->status = $request->status;
        $target->action_taken_by = Auth::user()->id;
        $target->remarks = $request->remarks;
        $target->progress = !empty($request->progress) ? $request->progress : 0;
        if ($target->save()) {
            return response()->json('success');
        }
    }

}
