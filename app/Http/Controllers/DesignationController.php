<?php

namespace App\Http\Controllers;

use Validator;
use App\Designation;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class DesignationController extends Controller {

    private $controller = 'Designation';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $targetArr = Designation::select('designation.*')->orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Designation::select('name')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('short_name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('designation.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) &&  ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/designation?page=' . $page);
        }

        return view('designation.index')->with(compact('targetArr', 'qpArr', 'status','nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('designation.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:designation',
                    'short_name' => 'required|unique:designation',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('designation/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Designation;
        $target->name = $request->name;
        $target->short_name = $request->short_name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.DESIGNATION_CREATED_SUCCESSFULLY'));
            return redirect('designation');
        } else {
            Session::flash('error', __('label.DESIGNATION_COULD_NOT_BE_CREATED'));
            return redirect('designation/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Designation::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('designation');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('designation.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Designation::find($id);
        $presentOrder = $target->order;
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:designation,name,' . $id,
                    'short_name' => 'required|unique:designation,short_name,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('designation/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->short_name = $request->short_name;
        $target->order = $request->order;
        $target->status = $request->status;
        
        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.DESIGNATION_UPDATED_SUCCESSFULLY'));
            return redirect('designation' . $pageNumber);
        } else {
            Session::flash('error', __('label.DESIGNATION_COULD_NOT_BE_UPDATED'));
            return redirect('designation/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Designation::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'User' => ['1' => 'designation_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return redirect('designation' . $pageNumber);
                }
            }
        }
        
        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.DESIGNATION_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.DESIGNATION_COULD_NOT_BE_DELETED'));
        }
        return redirect('designation' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('designation?' . $url);
    }

}