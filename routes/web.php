<?php

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();
//global $prefix;
$prefix = env('PREFIX');
//exit;
Route::group(['middleware' => 'auth'], function () use($prefix) {

    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
    Route::post('/dashboard/getBugDetails', 'Admin\DashboardController@getBugDetails')->name('dashboard.getBugDetails');
    Route::post('/dashboard/getTaskWiseProject', 'Admin\DashboardController@getTaskWiseProject')->name('dashboard.getTaskWiseProject');

    //setRecordPerPage
    Route::post('setRecordPerPage', 'UserController@setRecordPerPage');
    Route::get('changePassword', 'UserController@changePassword');
    Route::post('changePassword', 'UserController@updatePassword');

    /* Acl Access To Methods */
    Route::get('aclAccessToMethods', 'AclAccessToMethodsController@index');
    Route::get('aclAccessToMethods/addAccessMethod', 'AclAccessToMethodsController@addAccessMethod');
    Route::post('aclAccessToMethods/accessToMethodSave', 'AclAccessToMethodsController@accessToMethodSave');
    Route::post('aclAccessToMethods/getAccessMethod', 'AclAccessToMethodsController@getAccessMethod');


    //Task TO Project ajax method
    Route::post('taskToProject/getTasks', 'TaskToProjectController@getTasks')->name('taskToProject.getTasks');

    //Project Member To Task ajax method
    Route::post('memberToTask/getTasks', 'MemberToTaskController@getTasks')->name('memberToTask.getTasks');
    Route::post('memberToTask/getSingleUserTasks', 'MemberToTaskController@getSingleUserTasks')->name('memberToTask.getSingleUserTasks');

    //Company ajax method
    Route::post('company/getCompanyType', 'CompanyController@getCompanyType')->name('company.getCompanyType');

    //Bug Management ajax method
    Route::post('bug/getReportedBy', 'BugController@getReportedBy')->name('bug.getReportedBy');
    Route::post('bug/viewDetails', 'BugController@viewDetails')->name('bug.viewDetails');

    //support Team ajax method
    Route::post('supportTeam/getSupportedPerson', 'SupportTeamController@getSupportedPerson')->name('supportTeam.getSupportedPerson');

    //Bug Resolution ajax method
    Route::post('bugResolution/viewDetails', 'BugResolutionController@viewDetails')->name('bugResolution.viewDetails');
    Route::post('bugResolution/changeStatus', 'BugResolutionController@changeStatus')->name('bugResolution.changeStatus');
    Route::post('bugResolution/bugReassigned', 'BugResolutionController@bugReassigned')->name('bugResolution.bugReassigned');
    Route::post('bugResolution/getMemberAndBugData', 'BugResolutionController@getMemberAndBugData')->name('bugResolution.getMemberAndBugData');
    Route::post('bugResolution/updateLockedBy', 'BugResolutionController@updateLockedBy')->name('bugResolution.updateLockedBy');

    //Bug Follow connect to Bug Resolution after bug lock  ( ajax method ) 
    Route::post('bugFollowup/create', 'BugFollowUpController@openFollowUpModal')->name('bugFollowup.openFollowUpModal');
    Route::post('bugFollowup/store', 'BugFollowUpController@store')->name('bugFollowup.store');

    //contact Ajax method
    Route::post('contact/number', 'ContactController@viewContactNumber')->name('contact.number');
    Route::post('contact/email', 'ContactController@viewContactEmail')->name('contact.email');

    //contact meet up
    Route::post('contact/openMeetupModal', 'ContactController@openMeetupModal')->name('contact.openMeetupModal');
    Route::post('contact/storeMeetup', 'ContactController@storeMeetup')->name('contact.storeMeetup');
    Route::post('contact/editMeetup', 'ContactController@editMeetup')->name('contact.editMeetup');
    Route::post('contact/updateMeetup', 'ContactController@updateMeetup')->name('contact.updateMeetup');
    Route::delete('contact/deleteMeetup/{id}', 'ContactController@deleteMeetup')->name('contact.deleteMeetup');


    //contact Specialty
    Route::post('contact/openSpecialtyModal', 'ContactController@openSpecialtyModal')->name('contact.openSpecialtyModal');
    Route::post('contact/storeSpecialty', 'ContactController@storeSpecialty')->name('contact.storeSpecialty');
    Route::post('contact/editSpecialty', 'ContactController@editSpecialty')->name('contact.editSpecialty');
    Route::post('contact/updateSpecialty', 'ContactController@updateSpecialty')->name('contact.updateSpecialty');

    //contact Weakness
    Route::post('contact/openWeaknessModal', 'ContactController@openWeaknessModal')->name('contact.openWeaknessModal');
    Route::post('contact/storeWeekness', 'ContactController@storeWeekness')->name('contact.storeWeakness');
    Route::post('contact/editWeakness', 'ContactController@editWeakness')->name('contact.editWeakness');
    Route::post('contact/updateWeakness', 'ContactController@updateWeakness')->name('contact.updateWeakness');

    //contact Academic option
    Route::post('contact/openAcademic', 'ContactController@openAcademicModal')->name('contact.openAcademicModal');
    Route::post('contact/storeAcademic', 'ContactController@storeAcademic')->name('contact.storeAcademic');
    Route::post('contact/editAcademic', 'ContactController@editAcademic')->name('contact.editAcademic');
    Route::post('contact/updateAcademic', 'ContactController@updateAcademic')->name('contact.updateAcademic');
    Route::delete('contact/deleteAcademic/{id}', 'ContactController@deleteAcademic')->name('contact.Academic');


    //contact Professional option
    Route::post('contact/openProfessional', 'ContactController@openProfessionalModal')->name('contact.openProfessionalModal');
    Route::post('contact/storeProfessional', 'ContactController@storeProfessional')->name('contact.storeProfessional');
    Route::post('contact/editProfessional', 'ContactController@editProfessional')->name('contact.editProfessional');
    Route::post('contact/updateProfessional', 'ContactController@updateProfessional')->name('contact.updateProfessional');
    Route::delete('contact/deleteProfessional/{id}', 'ContactController@deleteProfessional')->name('contact.deleteProfessional');

    //contact Remarks option
    Route::post('contact/openRemarks', 'ContactController@openRemarksModal')->name('contact.openRemarksModal');
    Route::post('contact/storeRemarks', 'ContactController@storeRemarks')->name('contact.storeRemarks');
    Route::post('contact/editRemarks', 'ContactController@editRemarks')->name('contact.editRemarks');
    Route::post('contact/updateRemarks', 'ContactController@updateRemarks')->name('contact.updateRemarks');
    Route::delete('contact/deleteRemarks/{id}', 'ContactController@deleteRemarks')->name('contact.deleteRemarks');
});


//ACL ACCESS GROUP MIDDLEWARE
Route::group(['middleware' => ['auth', 'aclgroup']], function () use($prefix) {
    //user
    Route::post('user/filter/', 'UserController@filter');
    Route::get('user', 'UserController@index')->name('user.index');
    Route::get('user/create', 'UserController@create')->name('user.create');
    Route::post('user', 'UserController@store')->name('user.store');
    Route::get('user/{id}/edit', 'UserController@edit')->name('user.edit');
    Route::patch('user/{id}', 'UserController@update')->name('user.update');
    Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');

    //department
    Route::post('department/filter/', 'DepartmentController@filter');
    Route::get('department', 'DepartmentController@index')->name('department.index');
    Route::get('department/create', 'DepartmentController@create')->name('department.create');
    Route::post('department', 'DepartmentController@store')->name('department.store');
    Route::get('department/{id}/edit', 'DepartmentController@edit')->name('department.edit');
    Route::patch('department/{id}', 'DepartmentController@update')->name('department.update');
    Route::delete('department/{id}', 'DepartmentController@destroy')->name('department.destroy');

    //designation
    Route::post('designation/filter/', 'DesignationController@filter');
    Route::get('designation', 'DesignationController@index')->name('designation.index');
    Route::get('designation/create', 'DesignationController@create')->name('designation.create');
    Route::post('designation', 'DesignationController@store')->name('designation.store');
    Route::get('designation/{id}/edit', 'DesignationController@edit')->name('designation.edit');
    Route::patch('designation/{id}', 'DesignationController@update')->name('designation.update');
    Route::delete('designation/{id}', 'DesignationController@destroy')->name('designation.destroy');

    //Cofiguration
    Route::get('configuration', 'ConfigurationController@index')->name('configuration.index');
    Route::get('configuration/{id}/edit', 'ConfigurationController@edit')->name('configuration.edit');
    Route::patch('configuration/{id}', 'ConfigurationController@update')->name('configuration.update');

    //ppe
    Route::post('ppe/filter/', 'PpeController@filter');
    Route::get('ppe', 'PpeController@index')->name('ppe.index');
    Route::get('ppe/create', 'PpeController@create')->name('ppe.create');
    Route::post('ppe', 'PpeController@store')->name('ppe.store');
    Route::get('ppe/{id}/edit', 'PpeController@edit')->name('ppe.edit');
    Route::patch('ppe/{id}', 'PpeController@update')->name('ppe.update');
    Route::delete('ppe/{id}', 'PpeController@destroy')->name('ppe.destroy');


    //user group
    Route::post('userGroup/filter/', 'UserGroupController@filter');
    Route::get('userGroup', 'UserGroupController@index')->name('userGroup.index');
    Route::get('userGroup/create', 'UserGroupController@create')->name('userGroup.create');
    Route::post('userGroup', 'UserGroupController@store')->name('userGroup.store');
    Route::get('userGroup/{id}/edit', 'UserGroupController@edit')->name('userGroup.edit');
    Route::patch('userGroup/{id}', 'UserGroupController@update')->name('userGroup.update');
    Route::delete('userGroup/{id}', 'UserGroupController@destroy')->name('userGroup.destroy');

    //acl User Group To Access
    Route::get('aclUserGroupToAccess/moduleAccessControl', 'AclUserGroupToAccessController@moduleAccessControl');
    Route::post('aclUserGroupToAccess/relateUserGroupToAccess/', 'AclUserGroupToAccessController@relateUserGroupToAccess');
    Route::post('aclUserGroupToAccess/getAccessControl/', 'AclUserGroupToAccessController@getAccess');
    Route::get('aclUserGroupToAccess/userGroupToAccess', 'AclUserGroupToAccessController@userGroupToAccess');
    Route::post('aclUserGroupToAccess/getUserGroupListToRevoke', 'AclUserGroupToAccessController@getUserGroupListToRevoke');
    Route::post('aclUserGroupToAccess/revokeUserGroupAccess', 'AclUserGroupToAccessController@revokeUserGroupAccess');

    //Occupation
    Route::post('occupation/filter/', 'OccupationController@filter');
    Route::get('occupation', 'OccupationController@index')->name('occupation.index');
    Route::get('occupation/create', 'OccupationController@create')->name('occupation.create');
    Route::post('occupation', 'OccupationController@store')->name('occupation.store');
    Route::get('occupation/{id}/edit', 'OccupationController@edit')->name('occupation.edit');
    Route::patch('occupation/{id}', 'OccupationController@update')->name('occupation.update');
    Route::delete('occupation/{id}', 'OccupationController@destroy')->name('occupation.destroy');


    //Speciality
    Route::post('speciality/filter/', 'SpecialityController@filter');
    Route::get('speciality', 'SpecialityController@index')->name('speciality.index');
    Route::get('speciality/create', 'SpecialityController@create')->name('speciality.create');
    Route::post('speciality', 'SpecialityController@store')->name('speciality.store');
    Route::get('speciality/{id}/edit', 'SpecialityController@edit')->name('speciality.edit');
    Route::patch('speciality/{id}', 'SpecialityController@update')->name('speciality.update');
    Route::delete('speciality/{id}', 'SpecialityController@destroy')->name('speciality.destroy');

    //Weakness
    Route::post('weakness/filter/', 'WeaknessController@filter');
    Route::get('weakness', 'WeaknessController@index')->name('weakness.index');
    Route::get('weakness/create', 'WeaknessController@create')->name('weakness.create');
    Route::post('weakness', 'WeaknessController@store')->name('weakness.store');
    Route::get('weakness/{id}/edit', 'WeaknessController@edit')->name('weakness.edit');
    Route::patch('weakness/{id}', 'WeaknessController@update')->name('weakness.update');
    Route::delete('weakness/{id}', 'WeaknessController@destroy')->name('weakness.destroy');

    //Company
    Route::post('company/filter/', 'CompanyController@filter');
    Route::get('company', 'CompanyController@index')->name('company.index');
    Route::get('company/create', 'CompanyController@create')->name('company.create');
    Route::post('company/store', 'CompanyController@store')->name('company.store');
    Route::get('company/{id}/edit', 'CompanyController@edit')->name('company.edit');
    Route::patch('company/{id}', 'CompanyController@update')->name('company.update');
    Route::delete('company/{id}', 'CompanyController@destroy')->name('company.destroy');

    //Contact Management
    Route::get('company/{id}/contact', 'ContactController@index')->name('contact.index');
    Route::post('company/{id}/contact/filter/', 'ContactController@filter')->name('contact.filter');
    Route::get('company/{id}/contact/create', 'ContactController@create')->name('contact.create');
    Route::post('company/contact/store', 'ContactController@store')->name('contact.store');
    Route::get('contact/{id}/details', 'ContactController@details')->name('contact.details');
    Route::get('contact/{id}/edit', 'ContactController@edit')->name('contact.edit');
    Route::patch('contact/{id}', 'ContactController@update')->name('contact.update');
    Route::delete('company/{companyId}/contact/{id}', 'ContactController@destroy')->name('contact.destroy');


    //Industry
    Route::post('industry/filter/', 'IndustryController@filter');
    Route::get('industry', 'IndustryController@index')->name('industry.index');
    Route::get('industry/create', 'IndustryController@create')->name('industry.create');
    Route::post('industry', 'IndustryController@store')->name('industry.store');
    Route::get('industry/{id}/edit', 'IndustryController@edit')->name('industry.edit');
    Route::patch('industry/{id}', 'IndustryController@update')->name('industry.update');
    Route::delete('industry/{id}', 'IndustryController@destroy')->name('industry.destroy');

    //Department
    Route::post('department/filter/', 'DepartmentController@filter');
    Route::get('department', 'DepartmentController@index')->name('department.index');
    Route::get('department/create', 'DepartmentController@create')->name('department.create');
    Route::post('department', 'DepartmentController@store')->name('department.store');
    Route::get('department/{id}/edit', 'DepartmentController@edit')->name('department.edit');
    Route::patch('department/{id}', 'DepartmentController@update')->name('department.update');
    Route::delete('department/{id}', 'DepartmentController@destroy')->name('department.destroy');

    //Project
    Route::post('project/filter/', 'ProjectController@filter');
    Route::get('project', 'ProjectController@index')->name('project.index');
    Route::get('project/create', 'ProjectController@create')->name('project.create');
    Route::post('project/store', 'ProjectController@store')->name('project.store');
    Route::get('project/{id}/edit', 'ProjectController@edit')->name('project.edit');
    Route::get('project/{id}/ganttChart', 'ProjectController@ganttChart')->name('project.ganttChart');
    Route::patch('project/{id}', 'ProjectController@update')->name('project.update');
    Route::delete('project/{id}', 'ProjectController@destroy')->name('project.destroy');


    //Project Role
    Route::post('projectRole/filter/', 'ProjectRoleController@filter');
    Route::get('projectRole', 'ProjectRoleController@index')->name('projectRole.index');
    Route::get('projectRole/create', 'ProjectRoleController@create')->name('projectRole.create');
    Route::post('projectRole/store', 'ProjectRoleController@store')->name('projectRole.store');
    Route::get('projectRole/{id}/edit', 'ProjectRoleController@edit')->name('projectRole.edit');
    Route::patch('projectRole/{id}', 'ProjectRoleController@update')->name('projectRole.update');
    Route::delete('projectRole/{id}', 'ProjectRoleController@destroy')->name('projectRole.destroy');

    //Task
    Route::post('task/filter/', 'TaskController@filter');
    Route::get('task', 'TaskController@index')->name('task.index');
    Route::get('task/create', 'TaskController@create')->name('task.create');
    Route::post('task/store', 'TaskController@store')->name('task.store');
    Route::get('task/{id}/edit', 'TaskController@edit')->name('task.edit');
    Route::patch('task/{id}', 'TaskController@update')->name('task.update');
    Route::delete('task/{id}', 'TaskController@destroy')->name('task.destroy');

    //Team Management
    Route::post('projectTeam/filter/', 'ProjectTeamController@filter');
    Route::get('projectTeam', 'ProjectTeamController@index')->name('projectTeam.index');
    Route::get('projectTeam/create', 'ProjectTeamController@create')->name('projectTeam.create');
    Route::post('projectTeam/store', 'ProjectTeamController@store')->name('projectTeam.store');
    Route::get('projectTeam/{id}/edit', 'ProjectTeamController@edit')->name('projectTeam.edit');
    Route::patch('projectTeam/{id}', 'ProjectTeamController@update')->name('projectTeam.update');
    Route::delete('projectTeam/{id}', 'ProjectTeamController@destroy')->name('projectTeam.destroy');

    //Task TO Project
    Route::get('taskToProject', 'TaskToProjectController@create')->name('taskToProject.create');
    Route::post('taskToProject/store', 'TaskToProjectController@store')->name('taskToProject.store');

    //Project Member To Task
    Route::get('memberToTask', 'MemberToTaskController@create')->name('memberToTask.create');
    Route::post('memberToTask/store', 'MemberToTaskController@store')->name('memberToTask.store');


    //Project Status
    Route::post('projectStatus/filter/', 'ProjectStatusController@filter');
    Route::get('projectStatus', 'ProjectStatusController@index')->name('projectStatus.index');
    Route::get('projectStatus/create', 'ProjectStatusController@create')->name('projectStatus.create');
    Route::post('projectStatus/store', 'ProjectStatusController@store')->name('projectStatus.store');
    Route::get('projectStatus/{id}/edit', 'ProjectStatusController@edit')->name('projectStatus.edit');
    Route::patch('projectStatus/{id}', 'ProjectStatusController@update')->name('projectStatus.update');
    Route::delete('projectStatus/{id}', 'ProjectStatusController@destroy')->name('projectStatus.destroy');

    //Bug's Category
    Route::post('bugCategory/filter/', 'BugCategoryController@filter');
    Route::get('bugCategory', 'BugCategoryController@index')->name('bugCategory.index');
    Route::get('bugCategory/create', 'BugCategoryController@create')->name('bugCategory.create');
    Route::post('bugCategory/store', 'BugCategoryController@store')->name('bugCategory.store');
    Route::get('bugCategory/{id}/edit', 'BugCategoryController@edit')->name('bugCategory.edit');
    Route::patch('bugCategory/{id}', 'BugCategoryController@update')->name('bugCategory.update');
    Route::delete('bugCategory/{id}', 'BugCategoryController@destroy')->name('bugCategory.destroy');


    //Reporting Medium
    Route::post('reportingMedium/filter/', 'ReportingMediumController@filter');
    Route::get('reportingMedium', 'ReportingMediumController@index')->name('reportingMedium.index');
    Route::get('reportingMedium/create', 'ReportingMediumController@create')->name('reportingMedium.create');
    Route::post('reportingMedium/store', 'ReportingMediumController@store')->name('reportingMedium.store');
    Route::get('reportingMedium/{id}/edit', 'ReportingMediumController@edit')->name('reportingMedium.edit');
    Route::patch('reportingMedium/{id}', 'ReportingMediumController@update')->name('reportingMedium.update');
    Route::delete('reportingMedium/{id}', 'ReportingMediumController@destroy')->name('reportingMedium.destroy');

    //Point of Contact (POC)
    Route::post('pointOfContact/filter/', 'PointOfContactController@filter');
    Route::get('pointOfContact', 'PointOfContactController@index')->name('pointOfContact.index');
    Route::get('pointOfContact/create', 'PointOfContactController@create')->name('pointOfContact.create');
    Route::post('pointOfContact/store', 'PointOfContactController@store')->name('pointOfContact.store');
    Route::get('pointOfContact/{id}/edit', 'PointOfContactController@edit')->name('pointOfContact.edit');
    Route::patch('pointOfContact/{id}', 'PointOfContactController@update')->name('pointOfContact.update');
    Route::delete('pointOfContact/{id}', 'PointOfContactController@destroy')->name('pointOfContact.destroy');

    //Bug Management
    Route::post('bug/filter/', 'BugController@filter');
    Route::get('bug', 'BugController@index')->name('bug.index');
    Route::get('bug/create', 'BugController@create')->name('bug.create');
    Route::post('bug/store', 'BugController@store')->name('bug.store');
    Route::get('bug/{id}/edit', 'BugController@edit')->name('bug.edit');
    Route::patch('bug/{id}', 'BugController@update')->name('bug.update');
    Route::delete('bug/{id}', 'BugController@destroy')->name('bug.destroy');
    Route::get('bug/file', 'BugController@file')->name('bug.file');
    Route::post('bug/upload', 'BugController@uploadSubmit')->name('bug.uploadSubmit');

    //Support Team
    Route::get('supportTeam/', 'SupportTeamController@create')->name('supportTeam.create');
    Route::post('supportTeam/store', 'SupportTeamController@store')->name('supportTeam.store');

    //Bug Resolution
    Route::post('bugResolution/filter/', 'BugResolutionController@filter');
    Route::get('bugResolution', 'BugResolutionController@index')->name('bugResolution.index');

    //Bug Report
    Route::get('bugReport', 'BugReportController@index')->name('bugReport.index');
    Route::post('bugReport/filter', 'BugReportController@filter')->name('bugReport.filter');
    
    //Employee Report
    Route::get('employeeReport', 'EmployeeReportController@index')->name('employeeReport.index');
    Route::post('employeeReport/filter', 'EmployeeReportController@filter')->name('employeeReport.filter');
});



