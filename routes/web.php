<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// MTN API Routes
Route::get('/mtn/deposit', 'MTNRequestController@mTNDeposit')->name('mtn_deposit');
Route::get('/mtn/payment', 'MTNRequestController@mTNPayment')->name('mtn_payment');
Route::post('/mtn/deposit/post', 'MTNRequestController@postMTNDeposit')->name('post_mtn_deposit');
Route::post('/mtn/payment/post', 'MTNRequestController@postMTNPayment')->name('post_mtn_payment');

// User routes
Route::get('user/assigndefaults', ['as' => 'user-assign-defaults', 'uses' => 'UserController@userAssignDefaults']);
Route::get('user/{user}', array('as' => 'view-user-profile', 'uses' => 'UserController@viewUserProfile'));
Route::get('user/{user}/associates', array('as' => 'associates-list', 'uses' => 'UserController@associatesList'));
Route::get('user/{user}/checklist', array('as' => 'user-checklist', 'uses' => 'UserController@userChecklist'));
Route::get('user/{user}/edit', array('as' => 'edit-user-profile', 'uses' => 'UserController@editUserProfile'));

// User relationship routes
Route::get('user/{associated}/request_association/{associator}', array('as' => 'request-association', 'uses' => 'RelationshipController@requestAssociation'));
Route::get('user/{user}/delete_association_request/{user_relationship}', array('as' => 'delete-association-request', 'uses' => 'RelationshipController@deleteAssociationRequest'));
Route::get('user/{user}/cancel_delete_association_request/{user_relationship}', array('as' => 'cancel-delete-association-request', 'uses' => 'RelationshipController@cancelDeleteAssociationRequest'));
Route::get('user/{user}/accept_delete_association_request/{user_relationship}', array('as' => 'accept-delete-association-request', 'uses' => 'RelationshipController@acceptDeleteAssociationRequest'));

Route::get('user/{user}/accept_pending_association_request/{user_relationship}', array('as' => 'accept-pending-association-request', 'uses' => 'RelationshipController@acceptPendingAssociationRequest'));
Route::get('user/{user}/delete_pending_association_request/{user_relationship}', array('as' => 'delete-pending-association-request', 'uses' => 'RelationshipController@deletePendingAssociationRequest'));

// User posts
Route::post('user/{user}/update', array('as' => 'update-user-profile', 'uses' => 'UserController@updateUserProfile'));

// Admin routes
// manage content
Route::get('user/{user}/manage/{content_type}', array('as' => 'manage-content', 'uses' => 'ManagementController@manageContent'));
Route::get('user/{user}/publish/{content}', array('as' => 'publish-content', 'uses' => 'ManagementController@publishContent'));
Route::get('user/{user}/unpublish/{content}', array('as' => 'unpublish-content', 'uses' => 'ManagementController@unpublishContent'));
Route::get('user/{user}/approve/{content}', array('as' => 'approve-content', 'uses' => 'ManagementController@approveContent'));
Route::get('user/{user}/unapprove/{content}', array('as' => 'unapprove-content', 'uses' => 'ManagementController@unapproveContent'));
// manage users
Route::get('user/{user}/manage-users', array('as' => 'manage-users', 'uses' => 'ManagementController@manageUsers'));
Route::get('user/{user}/activate', array('as' => 'activate-user', 'uses' => 'ManagementController@activateUser'));
Route::get('user/{user}/deactivate', array('as' => 'deactivate-user', 'uses' => 'ManagementController@deactivateUser'));

// Social Login Authentication
Route::get('login/redirect/{provider}', ['as' => 'social-login', 'uses' => 'Auth\SocialAuthController@redirect']);
Route::get('login/callback/{provider}', 'Auth\SocialAuthController@callback');
