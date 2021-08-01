<?php

namespace App\Http\Controllers;

use Validator;
use App\BugCategory;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class BugCategoryController extends Controller {

    private $controller = 'BugCategory';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = BugCategory::orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->search;
        //echo $searchText;
        $nameArr = BugCategory::select('category_level')->get();
        $status = [null => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive'];
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('category_level', 'LIKE', '%' . $searchText . '%');
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
            return redirect('/bugCategory?page=' . $page);
        }
        return view('bugCategory.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('bugCategory.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
//        Helper::dump($request->all());
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'category_level' => 'required',
            'order' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('bugCategory/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new BugCategory;
        $target->category_level = $request->category_level;
        $target->order = $request->order;
        $target->status = !empty($request->status) ? $request->status : '';
        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.BUG_CATEGORY_CREATED_SUCCESSFULLY'));
            return redirect('bugCategory');
        } else {
            Session::flash('error', __('label.BUG_CATEGORY_COULD_NOT_BE_CREATED'));
            return redirect('bugCategory/create' . $pageNumber);
        }
    }

    public function edit(Request $request) {
        $target = BugCategory::findOrFail($request->id);
//        Helper::dump($target->toArray());
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('bugCategory');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('bugCategory.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request) {
//        Helper::dump($request->all());
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = [
            'category_level' => 'required',
            'order' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('bugCategory/' . $request->id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = BugCategory::findOrFail($request->id);
        $presentOrder = $target->order;
        $target->category_level = $request->category_level;
        $target->order = !empty($request->order) ? $request->order : '';
        $target->status = $request->status;
        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.BUG_CATEGORY_UPDATED_SUCCESSFULLY'));
            return redirect('bugCategory' . $pageNumber);
        } else {
            Session::flash('error', __('label.BUG_CATEGORY_COULD_NOT_BE_UPDATED'));
            return redirect('bugCategory/' . $request->id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request) {
        $target = BugCategory::findOrFail($request->id);
//        Helper::dump($target->toArray());
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('success', __('label.BUG_CATEGORY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BUG_CATEGORY_COULD_NOT_BE_DELETED'));
        }
        return redirect('bugCategory' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('bugCategory?' . $url);
    }

}
