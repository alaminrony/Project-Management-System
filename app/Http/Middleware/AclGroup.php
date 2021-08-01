<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Route;
use Request;
use App\AclModule;
use App\AclAccess;
use App\AclUserGroupToAccess;
use App\AclAccessToMethods;

class AclGroup {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        //METHOD NAME & CONTROLLER NAME
        $methodNameArr = list(, $action) = explode('@', Route::getCurrentRoute()->getActionName());
        $methodName = strtolower($methodNameArr[1]);

        //Controller Name
        $controllerName = Request::segment(1);
        $controllerName = Request::route()->getName();
        $currentControllerFunction = Route::currentRouteAction();
        $currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
        $controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
        //Route Name
        $routeName = strtolower(Route::getFacadeRoot()->current()->uri());


        $userGroupId = Auth::user()->group_id;
        $aclAccessInfo = AclAccess::select('*')->get();
        $aclModuleInfo = AclModule::select('*')->get();
        $aclAccessToMethodInfo = AclAccessToMethods::orderBy('acl_access_id', 'asc')
                        ->select('*')->get();

        $accessGroupArr = AclUserGroupToAccess::where('group_id', $userGroupId)
                        ->orderBy('module_id', 'asc')
                        ->select('*')->get();

        //ACCESS ARR
        $accessArr = [];
        if (!$accessGroupArr->isEmpty()) {
            foreach ($accessGroupArr as $item) {
                $accessArr[$item->module_id][$item->access_id] = $item->access_id;
            }
        }

        //MODULE ARR
        $moduleIdArr = [];
        if (!$aclModuleInfo->isEmpty()) {
            foreach ($aclModuleInfo as $item) {
                if (strtolower($controllerName) == strtolower($item->route_name)) {
                    $moduleIdArr = $item->id;
                }
            }
        }

        $finalAccessArr = $accessList = [];
        if (!empty($moduleIdArr)) {
            $accessGroupToAccessMethod = AclUserGroupToAccess::join('acl_access_to_methods', 'acl_access_to_methods.acl_access_id', '=', 'acl_user_group_to_access.access_id')
                            ->where('acl_user_group_to_access.group_id', $userGroupId)
                            ->where('acl_user_group_to_access.module_id', $moduleIdArr)
                            ->select('acl_access_to_methods.acl_access_id', 'acl_access_to_methods.method_name'
                                    , 'acl_user_group_to_access.module_id')->get();

            
            if (!$accessGroupToAccessMethod->isEmpty()) {
                foreach ($accessGroupToAccessMethod as $item) {
                    if ($methodName == strtolower($item->method_name)) {
                        $accessList[$item->acl_access_id] = $item->method_name;
                    }
                }
            }
            
            //DYNAMIC ACCESS SYSTEM 

            if (!empty($accessList)) {
                foreach ($accessList as $accessId => $item) {
                    if (array_key_exists($accessId, $accessArr[$moduleIdArr])) {
                        $finalAccessArr[$accessId] = $item;
                    }
                }
            }
        }
        
        //ENDIF DYNAMIC ACCESS SYSTEM
        

        if (empty($finalAccessArr)) {
            return redirect('dashboard');
        }
        return $next($request);
    }

}
