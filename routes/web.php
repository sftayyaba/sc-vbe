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
Route::get('/data', function() {

    $crawler = Goutte::request('GET', 'https://propakistani.pk/category/tech-and-telecom/');

    $crawler->filter('#arve')->each(function ($node) {
    	// $namespaces = array();
    	 // $y=$node->filter();
    	 // echo $y->text();
    	 // $y=$node->filter('default|*>head|*>content-area');
    	 // $x=$node->filter('#primary');
    	 
     	 print($node->html());


    });

});