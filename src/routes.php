<?php
#Route::get('/backStages', 'DashboardController@index');
Route::get('/core',                         ['as' => 'core.index'               ,'uses' => 'CoreController@index']);





/*
	|--------------------------------------------------------------------------
	| Template Routes, roles = root
	|--------------------------------------------------------------------------
	|Route::group(['middleware' => 'roles','roles' => ['root']], function()
        {
        }
*/

    Route::get('/templates',                    ['as' => 'template.index'               ,'uses' => 'Entities\Template\TemplateController@index']);

    //templates edit & upate ONLY ADMINISTRATORS AND AUTH TEMPLATE
    Route::get('template/{id}/edit',            ['as' => 'template.edit',               'uses' => 'Entities\Template\TemplateController@edit']);  //checked by TemplatePolicy
    Route::patch('template/update/{id}',        ['as' => 'template.update',             'uses' => 'Entities\Template\TemplateController@update']);  //checked by TemplatePolicy


    //templates create & store ONLY ADMINISTRATORS
    Route::get('template/create',               [
        'as' => 'template.create',
        'uses' => 'Entities\Template\TemplateController@create',
        //'middleware' => ['roles'],			// A 'roles' middleware must be specified
        //'roles' => ['administrator'] 		    // Only an administrator can access this route
    ]);
    Route::post('template/store',               ['as' => 'template.store',
        'uses' => 'Entities\Template\TemplateController@store',
        //'middleware' => ['roles'],
        //'roles' => ['administrator'] 		    // Only an administrator can store in database
    ]);

    //template delete ONLY ROOT
    Route::delete('template/{templateid}',          [
        'as' => 'template.destroy',
        'uses' => 'Entities\Template\TemplateController@destroy',
        //'middleware' => ['roles'],
        //'roles' => ['root']                   // Only an root can delete from database
    ]);


/*
    |--------------------------------------------------------------------------
    | Component Routes roles = root
    |--------------------------------------------------------------------------
    |
*/

    //index
    Route::get('/components', 			    ['as' => 'component.index'		,'uses' => 'Entities\Component\ComponentController@index']);

    //edit form
    Route::get('/component/{id}/edit',	    ['as' => 'component.edit'		,'uses' => 'Entities\Component\ComponentController@edit']);

    //add form
    Route::get('/component/create', 		['as' => 'component.create'		,'uses' => 'Entities\Component\ComponentController@create']);

    //database
    Route::post('/component', 			    ['as' => 'component.store'		,'uses' => 'Entities\Component\ComponentController@store']);
    Route::patch('/component/{id}', 		['as' => 'component.update'		,'uses' => 'Entities\Component\ComponentController@update']);
    //Route::put('/component/{id}', 		['as' => 'component.update.put' ,'uses' => 'ComponentController@update']);//??
    Route::delete('/component/{id}', 	    ['as' => 'component.destroy' 	,'uses' => 'Entities\Component\ComponentController@destroy']);



/*
    |--------------------------------------------------------------------------
    | Reference Routes roles = Administrator
    |--------------------------------------------------------------------------
    |
*/

//Route::group(['middleware' => 'roles','roles' => ['adminstrator']], function()
//{
    //index
    Route::get('/references', 			    ['as' => 'reference.index',     'uses' => 'Entities\Reference\ReferenceController@index']);

    //edit form
    Route::get('/reference/{id}/edit',	    ['as' => 'reference.edit',      'uses' => 'Entities\Reference\ReferenceController@edit']);

    //add form
    Route::get('/reference/create',         ['as' => 'reference.create',    'uses' => 'Entities\Reference\ReferenceController@selectReferenceTypeBeforeCreate']);

/*
 * CUSTOM (bookmarks)
 * */

	//index backStage/reference/bookmarks
	Route::get('/reference/{component_name}',               ['as' => 'reference.component.index',           'uses' => 'Entities\Reference\ReferenceController@getComponentIndex']);
	Route::get('/reference/{component_name}/data',         ['as' => 'reference.component.data',            'uses' => 'Entities\Reference\ReferenceController@componentIndexData']);
	//Route::get('/reference/all/data',                           ['as' => 'reference.all.data',           'uses' => 'ReferenceController@allIndexData']);


//Route::get('/charts',                                   ['as' => 'chart.all.index',          'uses' => 'ChartController@getAllIndex']);
//Route::get('/chart/all/data',                           ['as' => 'chart.all.data',           'uses' => 'ChartController@allIndexData']);



/*
 * END CUSTOM
 *
 * */



    //edit form, called from sitemap
    Route::get('/reference/{id}/edit/{sitemap_id}',
                                            ['as' => 'reference.edit.fromsitemap',
                                                                            'uses' => 'Entities\Reference\ReferenceController@edit']);

    //add form, called from sitemap
    Route::get('/reference/create/{sitemap_id}/{component_id}/{referencetype_id}',
                                            ['as' => 'reference.create.fromsitemap',
                                                                            'uses' => 'Entities\Reference\ReferenceController@createCalledFromSitemap']);



	Route::get('/reference/create/{component_id}/{referencetype_id}',
		['as' => 'reference.create.fromComponentIndex',
			'uses' => 'Entities\Reference\ReferenceController@createCalledComponentIndex']);



/*

    //add form
    //ORG=> Route::get('/reference/create', 		['as' => 'reference.create'		,'uses' => 'ReferenceController@create']);



    Route::get('/reference/{template_slug}/create',
        ['as' => 'reference.template.create'    ,'uses' => 'ReferenceController@create']);

    //database
    Route::post('/reference', 			    ['as' => 'reference.store'		,'uses' => 'ReferenceController@store']);
    Route::patch('/reference/{id}', 		['as' => 'reference.update'		,'uses' => 'ReferenceController@update']);
    //Route::put('/reference/{id}', 		['as' => 'reference.update.put' ,'uses' => 'ReferenceController@update']);//??
    Route::delete('/reference/{id}', 	    ['as' => 'reference.destroy' 	,'uses' => 'ReferenceController@destroy']);
*/

    //database
    Route::post('/reference', 			    ['as' => 'reference.store'		,'uses' => 'Entities\Reference\ReferenceController@store']);
    Route::patch('/reference/{id}', 		['as' => 'reference.update'		,'uses' => 'Entities\Reference\ReferenceController@update']);


    Route::delete('/reference/{id?}', 	    ['as' => 'reference.destroy' 	,'uses' => 'Entities\Reference\ReferenceController@destroy']);
//});


//COMPANY
//index
Route::get('/companiesORG', 			 ['as' => 'company.index'		,'uses' => 'Entities\Company\CompanyController@indexORG']);

Route::get('/companies',                ['as' => 'company.all.index',           'uses' => 'Entities\Company\CompanyController@getAllIndex']);
Route::get('/companies/all/data',            ['as' => 'company.all.data',       'uses' => 'Entities\Company\CompanyController@allIndexData']);

//edit form
Route::get('/company/{id}/edit',	    ['as' => 'company.edit'		,'uses' => 'Entities\Company\CompanyController@edit']);

//add form
Route::get('/company/create', 		['as' => 'company.create'		,'uses' => 'Entities\Company\CompanyController@create']);

//database
Route::post('/company', 			    ['as' => 'company.store'		,'uses' => 'Entities\Company\CompanyController@store']);
Route::patch('/company/{id}', 		['as' => 'company.update'		,'uses' => 'Entities\Company\CompanyController@update']);
//Route::put('/component/{id}', 		['as' => 'component.update.put' ,'uses' => 'ComponentController@update']);//??
Route::delete('/company/{id?}', 	    ['as' => 'company.destroy' 	,'uses' => 'Entities\Company\CompanyController@destroy']);
