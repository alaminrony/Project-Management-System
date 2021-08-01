<?php

namespace App\Http\Controllers;

use Validator;
use App\AclUserGroupToAccess;
use App\AclModuleToAccess;
use App\UserGroup;
use App\AclAccess;
use App\AclModule;
use Auth;
use Session;
use Redirect;
use Response;
use Illuminate\Http\Request;

class AclUserGroupToAccessController extends Controller {

    // START :: Function for Create Group To Access
    public function userGroupToAccess() {
        $userGroupList = ['0' => __('label.SELECT_GROUP_OPT')] + UserGroup::pluck('name', 'id')->toArray();
        return view('aclUserGroupAccess.create')->with(compact('userGroupList'));
    }

    // END :: Function for Create Group To Access
    //START :: Function for get Access Form
    public function getAccess(Request $request) {

        $accessList = AclAccess::pluck('name', 'id')->toArray();
        $moduleArr = AclModule::pluck('name', 'id')->toArray();
        $groupId = $request->group_id;

        //--------- Create array to easily access module and access  ----------------//
        $moduleToAccessArr = AclModuleToAccess::select('acl_module_to_access.module_id', 'acl_module_to_access.access_id')
                        ->orderBy('acl_module_to_access.module_id', 'asc')->orderBy('acl_module_to_access.access_id', 'asc')->get();

        $userGroupToAccessArr = AclUserGroupToAccess::select('acl_user_group_to_access.module_id', 'acl_user_group_to_access.access_id')
                        ->where('acl_user_group_to_access.group_id', '=', $request->group_id)
                        ->orderBy('acl_user_group_to_access.module_id', 'asc')->orderBy('acl_user_group_to_access.access_id', 'asc')->get();


        $moduleAccessListArr = [];
        $moduleToGroupAccessListArr = [];
        $moduleToGroupAccessListFinalArr = [];

        //Form a Tree with Module Access Relationship from
        //Module_to_Access Table
        if (!$moduleToAccessArr->isEmpty()) {
            foreach ($moduleToAccessArr as $ma) {
                $moduleAccessListArr[$ma->module_id][$ma->access_id] = true;
            }
        }

        //Form a Tree with Module Access Relationship from
        //User_group_Module_to_Access Table
        if (!$userGroupToAccessArr->isEmpty()) {
            foreach ($userGroupToAccessArr as $ma) {
                $moduleToGroupAccessListArr[$ma->module_id][$ma->access_id] = true;
            }
        }

        //Form the Refined Tree merging both Trees found in upper section
        if (!empty($moduleAccessListArr)) {
            foreach ($moduleAccessListArr as $module_id => $accessArr) {
                foreach ($accessArr as $access => $val) {
                    if (isset($moduleAccessListArr[$module_id][$access]) && isset($moduleToGroupAccessListArr[$module_id][$access])) {
                        $moduleToGroupAccessListFinalArr[$module_id][$access] = 2;
                    } else {
                        $moduleToGroupAccessListFinalArr[$module_id][$access] = 1;
                    }//EOF - If issset
                }//EOF - foreach Access
            }//EOF - foreach ModuleLIst
        }//EOF - if !empty
        //-----------------------------------------------------------------//

        $view = view('aclUserGroupAccess.showGroupAccessForm'
                , compact('accessList', 'moduleArr', 'moduleToGroupAccessListFinalArr'
                        , 'userGroupToAccessArr', 'groupId'))->render();
        return response()->json(['html' => $view]);
    }

    //END :: Function for get Access Form

    public function relateUserGroupToAccess(Request $request) {
        $moduleArr = AclModule::pluck('name', 'id')->toArray();

        $rules = $message = array();
        if (empty($request->group_id)) {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.USER_GROUP_MUST_BE_SELECTED')], 401);
        }


        if (empty($request->module)) {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.MODULE_MUST_BE_SELECTED')], 401);
        } else {
            foreach ($request->module as $key => $moduleId) {
                if (empty($request->module_access[$moduleId])) {
                    $rules['module_access[' . $moduleId . ']'] = 'required';

                    $message['module_access[' . $moduleId . ']' . '.required'] = __('label.ATLEAST_ONE_ACESS_SHOULD_BE_SELECTED_FOR') . ' ' . $moduleArr[$moduleId];
                } else {
                    $moduleToAccessArr = [];

                    AclUserGroupToAccess::where('group_id', $request->group_id)->delete();
                    $i = 0;
                    foreach ($request->module_access as $moduleId => $accessItem) {
                        foreach ($accessItem as $accessId => $val) {
                            $moduleToAccessArr[$i]['group_id'] = $request->group_id;
                            $moduleToAccessArr[$i]['module_id'] = $moduleId;
                            $moduleToAccessArr[$i]['access_id'] = $accessId;
                            $i++;
                        }
                    }//EOF --foreach
                    //Save Module Wise Group Access to "acl_user_group_to_access" table
                    if (!empty($moduleToAccessArr)) {
                        AclUserGroupToAccess::insert($moduleToAccessArr);
                        return Response::json(['success' => true, 'heading' => 'Success', 'message' => __('label.USER_GROUP_TO_MODULE_ACCESS_HAS_BEEN_ADDED_SUCCESSFULLY')], 200);
                    } else {
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.NO_DATA_HAS_BEEN_ADDED')], 401);
                    }//EOF --if
                }
            }
        }


        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Error', 'message' => $validator->errors()), 400);
        }
    }

    public function moduleAccessControl(Request $request) {
        $accessList = AclAccess::pluck('name', 'id')->toArray();
        $moduleArr = AclModule::pluck('name', 'id')->toArray();


        //--------- Create array to easily access module and access  ----------------//
        $moduleToAccessArr = AclModuleToAccess::select('acl_module_to_access.module_id', 'acl_module_to_access.access_id')
                        ->orderBy('acl_module_to_access.module_id', 'asc')->orderBy('acl_module_to_access.access_id', 'asc')->get();


        $moduleAccessListArr = [];

        //Form a Tree with Module Access Relationship from
        //Module_to_Access Table
        if (!$moduleToAccessArr->isEmpty()) {
            foreach ($moduleToAccessArr as $ma) {
                $moduleAccessListArr[$ma->module_id][$ma->access_id] = true;
            }
        }
        //echo '<pre>';print_r($moduleAccessListArr);exit;
        //-----------------------------------------------------------------//
        return view('aclUserGroupAccess.moduleWiseAccess')->with(compact('accessList', 'moduleArr', 'moduleAccessListArr'));
    }

    public function getUserGroupListToRevoke(Request $request) {
        
        $accessList = AclAccess::pluck('name', 'id')->toArray();
        $moduleArr = AclModule::pluck('name', 'id')->toArray();

        $userGroup = UserGroup::select('*')->where('id', $request->group_id)->first();
        $userGroupName = '';
        if(!empty($userGroup)){
            $userGroupName = $userGroup->name;
        }

        //--------- Create array to easily access module and access  ----------------//
        $moduleToAccessArr = AclModuleToAccess::select('acl_module_to_access.module_id', 'acl_module_to_access.access_id')
                        ->orderBy('acl_module_to_access.module_id', 'asc')->orderBy('acl_module_to_access.access_id', 'asc')->get();

        $userGroupToAccessArr = AclUserGroupToAccess::select('acl_user_group_to_access.module_id', 'acl_user_group_to_access.access_id')
                        ->where('acl_user_group_to_access.group_id', '=', $request->group_id)
                        ->orderBy('acl_user_group_to_access.module_id', 'asc')->orderBy('acl_user_group_to_access.access_id', 'asc')->get();


        $moduleAccessListArr = [];
        $moduleToGroupAccessListArr = [];

        //Form a Tree with Module Access Relationship from
        //Module_to_Access Table
        if (!$moduleToAccessArr->isEmpty()) {
            foreach ($moduleToAccessArr as $ma) {
                $moduleAccessListArr[$ma->module_id][$ma->access_id] = true;
            }
        }

        //Form a Tree with Module Access Relationship from
        //User_group_Module_to_Access Table
        if (!$userGroupToAccessArr->isEmpty()) {
            foreach ($userGroupToAccessArr as $ma) {
                $moduleToGroupAccessListArr[$ma->module_id][$ma->access_id] = true;
            }
        }
        
        $view = view('aclUserGroupAccess.revokeUserGroupAccess', compact('accessList', 'moduleArr'
                , 'moduleToGroupAccessListArr', 'userGroupName', 'userGroupToAccessArr'))->render();
        return response()->json(['html' => $view]);
    }
    
    public function revokeUserGroupAccess(Request $request) {
        if (empty($request->group_id)) {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.USER_GROUP_MUST_BE_SELECTED')], 401);
        }
        
        if(AclUserGroupToAccess::where('group_id', $request->group_id)->delete()){
            return Response::json(array('heading' => 'Success', 'message' => __('label.ALL_ACCESS_OF_THIS_USER_GROUP_REVOKED_SUCCESSFULLY')), 200);
        }else{
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_REVOKE_ACCESS_OF_THIS_USER_GROUP')), 401);
        }
        
        
        
    }

}
