<?php

Route::get('/','HomeController@exec');

Route::get('profile','HomeController@execProfile');
Route::get('leaderboard','HomeController@execLeaderBoard');
Route::get('game','HomeController@execGamePage');
Route::get('playgame/{gamename}','HomeController@execPlayGame');
Route::get('admintools','HomeController@execAdminTools');
Route::get('getdata','HomeController@execGetGameData');