<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\UserGroup;
use App\Department;
use App\Designation;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function __construct() {
        Validator::extend('complexPassword', function($attribute, $value, $parameters) {
            $password = $parameters[1];

            if (preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[!@#$%^&*()])(?=\S*[\d])\S*$/', $password)) {
                return true;
            }
            return false;
        });
    }

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $userGroupArr = UserGroup::pluck('name', 'id')->toArray();
        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;

        $departmentList = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        $targetArr = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                ->join('department', 'department.id', '=', 'users.department_id')
                ->join('designation', 'designation.id', '=', 'users.designation_id')
                ->select('user_group.name as group_name', 'users.group_id'
                        , 'users.id', 'users.first_name', 'users.last_name'
                        , 'users.username', 'users.photo', 'users.status', 'designation.name as designation_name'
                        , 'department.name as department_name', 'users.employee_id')
                ->orderBy('users.group_id', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = User::select('username')->orderBy('group_id', 'asc')->get();
        $userDepartmentOption = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + Designation::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $status = array('0' => __('label.SELECT_STATUS_OPT')) + ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')];

        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('users.username', 'LIKE', '%' . $searchText . '%');
            });
        }

        if (!empty($request->user_group)) {
            $targetArr = $targetArr->where('users.group_id', '=', $request->user_group);
        }
        if (!empty($request->department)) {
            $targetArr = $targetArr->where('users.department_id', '=', $request->department);
        }
        if (!empty($request->designation)) {
            $targetArr = $targetArr->where('users.designation_id', '=', $request->designation);
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('users.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/user?page=' . $page);
        }
        return view('user.index')->with(compact('qpArr', 'targetArr', 'groupList', 'departmentList', 'nameArr', 'userDepartmentOption', 'designationList', 'status'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $userGroupArr = UserGroup::orderBy('id', 'asc')->pluck('name', 'id', 'asc')->toArray();

        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;
        $departmentList = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + Designation::orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        return view('user.create')->with(compact('qpArr', 'groupList', 'departmentList', 'designationList'));
    }

    public function store(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        $rules = [
            'group_id' => 'required|not_in:0',
            'department_id' => 'required|not_in:0',
            'designation_id' => 'required|not_in:0',
            'employee_id' => 'required',
            'username' => 'required|unique:users|alpha_num',
            'password' => 'required|complex_password:,' . $request->password,
            'conf_password' => 'required|same:password'
        ];


        if (!empty($request->photo)) {
            $rules['photo'] = 'max:1024|mimes:jpeg,png,jpg';
        }

        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return redirect('user/create' . $pageNumber)
                            ->withInput($request->except('photo', 'password', 'conf_password'))
                            ->withErrors($validator);
        }

        //image crop image and save
        $imgName = null;
        if (!empty($request->crop_photo)) {
            $imgName = Auth::user()->id . uniqid() . ".png";
            $path = public_path() . "/uploads/user/" . $imgName;
            $croppedImg = $request->crop_photo;
            $img = substr($croppedImg, strpos($croppedImg, ",") + 1);
            $data = base64_decode($img);
            $success = file_put_contents($path, $data);
        }


        $target = new User;
        $target->group_id = $request->group_id;
        $target->department_id = $request->department_id;
        $target->designation_id = $request->designation_id;
        $target->employee_id = $request->employee_id;
        $target->first_name = $request->first_name;
        $target->last_name = $request->last_name;
        $target->nick_name = $request->nick_name;
        $target->email = $request->email;
        $target->phone = $request->phone;
        $target->username = $request->username;
        $target->password =  Hash::make($request->password);
        $target->photo = !empty($imgName) ? $imgName : '';
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.USER_CREATED_SUCCESSFULLY'));
            return redirect('user');
        } else {
            Session::flash('error', __('label.USER_COULD_NOT_BE_CREATED'));
            return redirect('user/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = User::find($id);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('user');
        }

        //passing param for custom function
        $qpArr = $request->all();

        $userGroupArr = UserGroup::orderBy('id', 'asc')->pluck('name', 'id', 'asc')->toArray();

        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;
        $departmentList = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + Designation::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
       
        return view('user.edit')->with(compact('target', 'qpArr', 'groupList', 'departmentList'
                , 'designationList'));
    }

    public function update(Request $request, $id) {
        $target = User::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = [
            'group_id' => 'required|not_in:0',
            'department_id' => 'required|not_in:0',
            'designation_id' => 'required|not_in:0',
            'employee_id' => 'required',
            'username' => 'required|alpha_num|unique:users,username,' . $id,
            'conf_password' => 'same:password',
        ];


        if (!empty($request->password)) {
            $rules['password'] = 'complex_password:,' . $request->password;
            $rules['conf_password'] = 'same:password';
        }

        if (!empty($request->photo)) {
            $rules['photo'] = 'max:1024|mimes:jpeg,png,gif,jpg';
        }

        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('user/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all)
                            ->withErrors($validator);
        }
        //image resize and save
        $imgName = null;
        if (!empty($request->crop_photo)) {
            if (!empty($target->photo)) {
				
                $prevfileName = 'public/uploads/user/' . $target->photo;

                if (File::exists($prevfileName)) {
                    File::delete($prevfileName);
                }
            }

            $imgName = Auth::user()->id . uniqid(). ".png";
            $path = public_path() . "/uploads/user/" . $imgName;
            $croppedImg = $request->crop_photo;
            $img = substr($croppedImg, strpos($croppedImg, ",") + 1);
            $data = base64_decode($img);
            $success = file_put_contents($path, $data);
        }
        
        
        $target->group_id = $request->group_id;
        $target->department_id = $request->department_id;
        $target->designation_id = $request->designation_id;
        $target->employee_id = $request->employee_id;
        $target->first_name = $request->first_name;
        $target->last_name = $request->last_name;
        $target->nick_name = $request->nick_name;
        $target->email = $request->email;
        $target->phone = $request->phone;
        $target->username = $request->username;
        if (!empty($request->password)) {
            $target->password = Hash::make($request->password);
        }
        $target->photo = !empty($imgName) ? $imgName : $target->photo;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.USER_UPDATED_SUCCESSFULLY'));
            return redirect('user' . $pageNumber);
        } else {
            Session::flash('error', __('label.USER_COULD_NOT_BE_UPDATED'));
            return redirect('user/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = User::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency
//        $dependencyArr = [
//            'BatchCard' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Buyer' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Certificate' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Demand' => ['1' => 'created_by'],
//            'Department' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Designation' => ['1' => 'created_by', '2' => 'updated_by'],
//            'DryerMachine' => ['1' => 'created_by', '2' => 'updated_by'],
//            'DryerType' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Factory' => ['1' => 'created_by', '2' => 'updated_by'],
//            'GarmentsType' => ['1' => 'created_by', '2' => 'updated_by'],
//            'HazardCategory' => ['1' => 'created_by', '2' => 'updated_by'],
//            'HazardClass' => ['1' => 'created_by', '2' => 'updated_by'],
//            'HydroMachine' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Machine' => ['1' => 'created_by', '2' => 'updated_by'],
//            'MachineModel' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Manufacturer' => ['1' => 'created_by', '2' => 'updated_by'],
//            'MfAddressBook' => ['1' => 'created_by', '2' => 'updated_by'],
//            'MeasureUnit' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Pictogram' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Ppe' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Product' => ['1' => 'created_by', '2' => 'updated_by'],
//            'ProductCategory' => ['1' => 'created_by', '2' => 'updated_by'],
//            'ProductCheckInMaster' => ['1' => 'created_by'],
//            'ProductFunction' => ['1' => 'created_by', '2' => 'updated_by'],
//            'ProductToManufacturer' => ['1' => 'created_by'],
//            'ProductToProcess' => ['1' => 'created_by'],
//            'ProductToSupplier' => ['1' => 'created_by'],
//            'ProductConsumptionMaster' => ['1' => 'created_by', '2' => 'approved_by'],
//            'Recipe' => ['1' => 'created_by', '2' => 'updated_by'],
//            'SecondaryUnit' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Style' => ['1' => 'created_by', '2' => 'updated_by'],
//            'SubstanceCas' => ['1' => 'created_by', '2' => 'updated_by'],
//            'SubstanceEc' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Supplier' => ['1' => 'created_by', '2' => 'updated_by'],
//            'SupplierType' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Wash' => ['1' => 'created_by', '2' => 'updated_by'],
//            'WashType' => ['1' => 'created_by', '2' => 'updated_by'],
//            'WeightProcess' => ['1' => 'created_by', '2' => 'updated_by'],
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
//                    return redirect('user' . $pageNumber);
//                }
//            }
//        }
        $fileName = 'public/uploads/user/' . $target->photo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        if ($target->delete()) {
            Session::flash('error', __('label.USER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.USER_COULD_NOT_BE_DELETED'));
        }
        return redirect('user' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&user_group=' . $request->user_group . '&department=' . $request->department . '&designation=' . $request->designation . '&status=' . $request->status;
        return Redirect::to('user?' . $url);
    }

    public function changePassword() {
        return view('user.changePassword');
    }

    public function updatePassword(Request $request) {
        $target = User::find(Auth::user()->id);

        $rules = [
            'password' => 'required|complex_password:,' . $request->password,
            'conf_password' => 'required',
        ];
        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('changePassword')
                            ->withInput($request->except('current_password', 'password', 'conf_password'))
                            ->withErrors($validator);
        }

        $target->password = Hash::make($request->password);
        if ($target->save()) {
            Session::flash('success', __('label.PASSWORD_UPDATED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PASSWORD_COULD_NOT_BE_UPDATED'));
        }
        return view('user.changePassword');
    }

    public function setRecordPerPage(Request $request) {
        $referrerArr = explode('?', URL::previous());
        $queryStr = '';
        if (!empty($referrerArr[1])) {
            $queryParam = explode('&', $referrerArr[1]);
            foreach ($queryParam as $item) {
                $valArr = explode('=', $item);
                if ($valArr[0] != 'page') {
                    $queryStr .= $item . '&';
                }
            }
        }

        $url = $referrerArr[0] . '?' . trim($queryStr, '&');

        if ($request->record_per_page > 999) {
            Session::flash('error', __('label.NO_OF_RECORD_MUST_BE_LESS_THAN_999'));
            return redirect($url);
        }

        if ($request->record_per_page < 1) {
            Session::flash('error', __('label.NO_OF_RECORD_MUST_BE_GREATER_THAN_1'));
            return redirect($url);
        }

        $request->session()->put('paginatorCount', $request->record_per_page);
        return redirect($url);
    }

}
