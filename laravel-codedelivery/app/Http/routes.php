<?php

Route::get('/home', function() {
    return view('home');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth.checkrole', 'as' => 'admin.'], function() {

    Route::get('categories',['as' => 'categories.index', 'uses' => 'CategoriesController@index']);
    Route::get('categories/create',['as' => 'categories.create', 'uses' => 'CategoriesController@create']);
    Route::get('categories/edit/{id}',['as' => 'categories.edit', 'uses' => 'CategoriesController@edit']);
    Route::post('categories/update/{id}',['as' => 'categories.update', 'uses' => 'CategoriesController@update']);
    Route::post('categories/store',['as' => 'categories.store', 'uses' => 'CategoriesController@store']);

    Route::get('clients',['as' => 'clients.index', 'uses' => 'ClientsController@index']);
    Route::get('clients/create',['as' => 'clients.create', 'uses' => 'ClientsController@create']);
    Route::get('clients/edit/{id}',['as' => 'clients.edit', 'uses' => 'ClientsController@edit']);
    Route::post('clients/update/{id}',['as' => 'clients.update', 'uses' => 'ClientsController@update']);
    Route::post('clients/store',['as' => 'clients.store', 'uses' => 'ClientsController@store']);

    Route::get('products',['as' => 'products.index', 'uses' => 'ProductsController@index']);
    Route::get('products/create',['as' => 'products.create', 'uses' => 'ProductsController@create']);
    Route::get('products/edit/{id}',['as' => 'products.edit', 'uses' => 'ProductsController@edit']);
    Route::post('products/update/{id}',['as' => 'products.update', 'uses' => 'ProductsController@update']);
    Route::post('products/store',['as' => 'products.store', 'uses' => 'ProductsController@store']);
    Route::get('products/destroy/{id}',['as' => 'products.destroy', 'uses' => 'ProductsController@destroy']);

    Route::get('orders',['as' => 'orders.index', 'uses' => 'OrdersController@index']);
    Route::get('orders/{id}',['as' => 'orders.edit', 'uses' => 'OrdersController@edit']);
    Route::post('orders/update/{id}',['as' => 'orders.update', 'uses' => 'OrdersController@update']);

});


