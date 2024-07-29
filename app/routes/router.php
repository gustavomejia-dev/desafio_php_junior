<?php 

use App\http\Route;

Route::get('/', 'LoginController@index');
Route::get('/register/user', 'RegisterUserController@index');
Route::get('/register/room/reserve', 'RoomController@index');
Route::get('/register/room', 'RoomController@register');

//
Route::post('/login', 'LoginController@login');
Route::post('/register/email/validar', 'RegisterUserController@verifyEmailExist');
Route::post('/register/user/store', 'RegisterUserController@store');
Route::post('/register/room/store', 'RoomController@store');
Route::post('/room/list', 'RoomController@listRooms');
Route::post('/room/list/bookings', 'RoomController@listBookings');
Route::post('/register/booking', 'RoomController@registerBooking');
Route::post('/user/logout', 'LoginController@logout');
//
Route::delete('/delete/room/booking', 'RoomController@deleteBooking');
Route::delete('/delete/room', 'RoomController@delete');

//

Route::put('/edit/room', 'RoomController@edit');