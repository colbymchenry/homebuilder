<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    return redirect('/home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/project', 'ProjectController@index');

Route::get('/lot', 'LotController@index');

Route::get('/task', 'TaskController@index');

Route::get('/house-plans', 'HousePlansController@indexPlans');

Route::get('/house-plan', 'HousePlansController@indexPlan');

Route::get('/house-plan-builder', 'HousePlansController@indexBuilder');

Route::get('/task-templates', 'TaskController@indexTemplates');

Route::get('/template', 'TaskController@indexTemplate');

Route::get('/vendors', 'VendorController@list');

Route::get('/vendor', 'VendorController@getInfo');

Route::get('/edit-vendor', 'VendorController@getIndex');

Route::post('/create-project', 'ProjectController@create');

Route::post('/delete-project', 'ProjectController@delete');

Route::post('/create-lot', 'LotController@create');

Route::post('/delete-lot', 'LotController@delete');

Route::post('/task-status-update', 'TaskController@updateStatus');

Route::post('/task-create', 'TaskController@create');

Route::post('/task-delete', 'TaskController@delete');

Route::post('/note-create', 'NoteController@create');

Route::post('/note-delete', 'NoteController@delete');

Route::post('/file-upload','FileController@upload');

Route::post('/create-house-plan','HousePlansController@createHousePlan');

Route::post('/delete-house-plan','HousePlansController@deleteHousePlan');

Route::post('/create-design-option','HousePlansController@createDesignOption');

Route::post('/delete-design-option','HousePlansController@deleteDesignOption');

Route::post('/create-design-category', 'HousePlansController@createDesignCategory');

Route::post('/delete-design-category', 'HousePlansController@deleteDesignCategory');

Route::post('/create-price-sheet','HousePlansController@createPriceSheet');

Route::post('/delete-price-sheet','HousePlansController@deletePriceSheet');

Route::post('/update-price-sheet','HousePlansController@updatePriceSheet');

Route::post('/create-template','TaskController@createTemplate');

Route::post('/delete-template','TaskController@deleteTemplate');

Route::post('/delete-template-task','TaskController@deleteTemplateTask');

Route::post('/create-template-task','TaskController@createTemplateTask');

Route::post('/sort-template-task','TaskController@sortTemplateTask');

Route::post('/set-lot-plan', 'LotController@setPlan');

Route::post('/buildout-pdf', 'PDFController@generatePDF_PlanBuildout');

Route::post('/create-vendor', 'VendorController@create');