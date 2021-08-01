<?php

namespace App\Http\Controllers;

use Validator;
use App\Task;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class TaskController extends Controller {

    private $controller = 'Task';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Task::orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->search;
        //echo $searchText;
        $nameArr = Task::select('title')->get();
        $status = [null => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive'];
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('title', 'LIKE', '%' . $searchText . '%');
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
            return redirect('/task?page=' . $page);
        }
        return view('task.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('task.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
//        Helper::dump($request->all());
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'title' => 'required',
            'order' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('task/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Task;
        $target->title = !empty($request->title) ? $request->title : '';
        $target->order = !empty($request->order) ? $request->order : '';
        $target->status = !empty($request->status) ? $request->status : '';
        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.TASK_CREATED_SUCCESSFULLY'));
            return redirect('task');
        } else {
            Session::flash('error', __('label.TASK_COULD_NOT_BE_CREATED'));
            return redirect('task/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Task::findOrFail($request->id);
//        Helper::dump($target->toArray());
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('task');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('task.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request) {
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = [
            'title' => 'required',
            'order' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('task/' . $request->id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = Task::findOrFail($request->id);
        $presentOrder = $target->order;
        $target->title = !empty($request->title) ? $request->title : '';
        $target->order = !empty($request->order) ? $request->order : '';
        $target->status = $request->status;
        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.TASK_UPDATED_SUCCESSFULLY'));
            return redirect('task' . $pageNumber);
        } else {
            Session::flash('error', __('label.TASK_COULD_NOT_BE_UPDATED'));
            return redirect('task/' . $request->id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request) {
        $target = Task::findOrFail($request->id);
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('success', __('label.TASK_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.TASK_COULD_NOT_BE_DELETED'));
        }
        return redirect('task' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('task?' . $url);
    }

}
