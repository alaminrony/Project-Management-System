<?php

namespace App\Http\Controllers;

use Validator;
use App\AclAccessToMethods;
use App\AclAccess;
use Auth;
use Session;
use Redirect;
use Helper;
use Response;
use Illuminate\Http\Request;

class AclAccessToMethodsController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        
        //fetching access method data
        $targetArr = AclAccessToMethods::join('acl_access', 'acl_access.id', '=', 'acl_access_to_methods.acl_access_id')
                ->select('acl_access_to_methods.*', 'acl_access.name as acl_access_name')
                ->orderBy('acl_access.id', 'asc')
                ->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/aclAccessToMethods?page=' . $page);
        }

        return view('aclAccessToMethods.index')->with(compact('targetArr', 'qpArr'));
    }

    public function addAccessMethod() {
        $aclAccessList = ['0' => __('label.SELECT_ACL_ACCESS_OPT')] + AclAccess::orderBy('id', 'asc')->pluck('name', 'id')->toArray();
        return view('aclAccessToMethods.addAccessMethod')->with(compact('qpArr', 'aclAccessList'));
    }

    public function getAccessMethod(Request $request) {
        $aclAccessId = $request->acl_access_id;
        $aclAccessMethodData = AclAccessToMethods::where('acl_access_id', $aclAccessId)
                        ->select('*')->get();

        $accessMethod = view('aclAccessToMethods.getAccessMethod', compact('aclAccessMethodData', 'aclAccessId', 'qpArr'))->render();
        return response()->json(['accessMethod' => $accessMethod]);
    }

    public function accessToMethodSave(Request $request) {
        $aclAccessIdArray = $request->aclAccessIdArray;

        if (!empty($aclAccessIdArray)) {
            $i = 0;
            $data = $errorArr = [];
            foreach ($aclAccessIdArray as $key => $item) {
                //validation check
                if (empty($item['method_name'])) {
                    $errorArr[] = __('label.METHOD_NAME_REQUIRED') . ' ' . ++$key . ' ' . __('label.ROW');
                    //validation check end
                    
                } else {
                    //collecting data
                    $data[$i]['acl_access_id'] = $request->acl_access_id;
                    $data[$i]['method_name'] = $item['method_name'];
                    $i++;
                }
            }
            //validation error msg
            if (!empty($errorArr)) {
                return Response::json(array('success' => false, 'message' => $errorArr), 400);
            }
        }

        if (empty($data)) {
            return Response::json(array('success' => false, 'message' => __('label.ATLEAST_ONE_METHOD_TO_BE_ADDED')), 401);
        } else {
            AclAccessToMethods::where('acl_access_id', $request->acl_access_id)->delete();
            if (AclAccessToMethods::insert($data)) {
                return Response::json(['success' => true], 200);
            } else {
                return Response::json(array('success' => false, 'message' => __('label.METHOD_NAME_COULD_NOT_BE_ADDED')), 401);
            }
        }
    }


}
