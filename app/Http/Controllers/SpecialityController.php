<?php

namespace App\Http\Controllers;

use Validator;
use App\Speciality;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class SpecialityController extends Controller {

    private $controller = 'Speciality';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Speciality::select('speciality.*')->orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Speciality::select('name')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('short_name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('speciality.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) &&  ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/speciality?page=' . $page);
        }

        return view('speciality.index')->with(compact('targetArr', 'qpArr', 'status','nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('speciality.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:speciality',
                    'short_name' => 'required|unique:speciality',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('speciality/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Speciality;
        $target->name = $request->name;
        $target->short_name = $request->short_name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.SPECIALITY_CREATED_SUCCESSFULLY'));
            return redirect('speciality');
        } else {
            Session::flash('error', __('label.SPECIALITY_COULD_NOT_BE_CREATED'));
            return redirect('speciality/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Speciality::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('speciality');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('speciality.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Speciality::find($id);
        $presentOrder = $target->order;
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:speciality,name,' . $id,
                    'short_name' => 'required|unique:speciality,short_name,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('speciality/' . $id . '/edit' . $pageNumber)
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
            Session::flash('success', __('label.SPECIALITY_UPDATED_SUCCESSFULLY'));
            return redirect('speciality' . $pageNumber);
        } else {
            Session::flash('error', __('label.SPECIALITY_COULD_NOT_BE_UPDATED'));
            return redirect('speciality/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Speciality::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
//        $dependencyArr = [
//            'User' => ['1' => 'speciality_id']
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
//                    return redirect('speciality' . $pageNumber);
//                }
//            }
//        }
        
        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.SPECIALITY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.SPECIALITY_COULD_NOT_BE_DELETED'));
        }
        return redirect('speciality' . $pageNumber);
    }

    public function filter(Request $request) {
        
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('speciality?' . $url);
    }

}