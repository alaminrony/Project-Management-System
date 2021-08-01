<?php

namespace App\Http\Controllers;

use Validator;
use App\ProjectRole;
use Auth;
use Session;
use Redirect;
use Helper;
use App\Industry;
use Illuminate\Http\Request;

class ProjectRoleController extends Controller {

    private $controller = 'ProjectRole';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = ProjectRole::orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->search;
        //echo $searchText;
        $nameArr = ProjectRole::select('name')->get();
        $status = [null => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive'];
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isNotEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/projectRole?page=' . $page);
        }
        return view('projectRole.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('projectRole.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
//        Helper::dump($request->all());
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'name' => 'required',
            'order' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('projectRole/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new ProjectRole;
        $target->name = !empty($request->name) ? $request->name : '';
        $target->order = !empty($request->order) ? $request->order : '';
        $target->status = !empty($request->status) ? $request->status : '';
        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.ROLE_CREATED_SUCCESSFULLY'));
            return redirect('projectRole');
        } else {
            Session::flash('error', __('label.ROLE_COULD_NOT_BE_CREATED'));
            return redirect('projectRole/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = ProjectRole::findOrFail($request->id);
//        Helper::dump($target->toArray());
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('project');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('projectRole.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request) {
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = [
            'name' => 'required',
            'order' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('projectRole/' . $request->id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = ProjectRole::findOrFail($request->id);
        $presentOrder = $target->order;
        $target->name = !empty($request->name) ? $request->name : '';
        $target->order = !empty($request->order) ? $request->order : '';
        $target->status = $request->status;
        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.ROLE_UPDATED_SUCCESSFULLY'));
            return redirect('projectRole' . $pageNumber);
        } else {
            Session::flash('error', __('label.ROLE_COULD_NOT_BE_UPDATED'));
            return redirect('projectRole/' . $request->id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request) {
        $target = ProjectRole::findOrFail($request->id);
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('success', __('label.ROLE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.ROLE_COULD_NOT_BE_DELETED'));
        }
        return redirect('projectRole' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('projectRole?' . $url);
    }

}
