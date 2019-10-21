<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/search_college_name/{name}','College_search@search_college_name');
Route::get('/get_college_id/{name}','College_search@get_college_id');
Route::get('/search_college/{name}','College_search@search_college');
Route::get('/college_profile/{id}','College_search@college_profie');
Route::get('/get_state','College_search@get_state');
Route::post('/get_state_cities','College_search@get_state_cities');
Route::get('/get_major','College_search@get_major');
Route::get('/city_by_name','College_search@city_by_name');
Route::post('/filter_search','College_search@filter_search');
Route::post('/user_register','User@register');
Route::post('/user_login','User@login');
Route::post('/add_favorite','User@add_favorite');
Route::post('/remove_favorite','User@remove_favorite');
Route::post('/get_user_data','User@get_user_data');
Route::post('/update_user_data','User@update_user_data');
Route::post('/get_favorite_college','User@get_favorite_college');
Route::post('/user_favorite_college','User@user_favorite_college');
Route::post('/email_check','User@email_check');
Route::post('/user_email','User@user_email');
Route::post('/get_hotel_city','College_search@get_hotel_city');
Route::get('/get_cities','College_search@get_cities');
Route::post('/add_favorite_article','User@add_favorite_articles');
Route::post('/user_favorite_articles','User@user_favorite_articles');
Route::post('/remove_favorite_article','User@remove_favorite_article');