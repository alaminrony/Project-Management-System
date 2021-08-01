<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\AclUserGroupToAccess;
use DB;
use Common;
use App\Bug;
use Auth;
use Helper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*', function ($view) {

            //get request notification number on topnavber in all views
            if (Auth::check()) {
                
                //Notification for new bug to Assign supported member
                $targetArr = Bug::join('support_team', 'support_team.project_id', '=', 'bug.project_id')
                        ->select('bug.id', 'bug.bug_title','bug.status','support_team.team_manager_id', 'support_team.support_persons_id')
                        ->where('bug.status','=','1')
                        ->get();
                
                $bugNotification = [];
                foreach($targetArr as $target){
                    $supMemberArr = json_decode($target->support_persons_id);
                    if(($target->team_manager_id == Auth::user()->id) || in_array(Auth::user()->id,$supMemberArr)){
                        $bugNotification[] = $target->bug_title;
                    }
                }
                $notification = count($bugNotification);

                //ACL ACCESS LIST
                $userAccessArr = Common::userAccess();
                
                
                

                $view->with([
                    'userAccessArr' => $userAccessArr,
                    'notification'=>$notification,
                ]);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
