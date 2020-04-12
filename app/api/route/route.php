<?php


use think\facade\Route;

Route::post('login','Auth/login');
Route::get('menus','Index/menus');