<?php
//STATIC PAGES
Route::get('/',                                ['as'=>'index',          'uses'=>'MainController@index']);
Route::get('/about',                           ['as'=>'about',          'uses'=>'MainController@about']);
Route::get('/contacts',                        ['as'=>'contacts',       'uses'=>'MainController@contacts']);

//ARTICLES
Route::post('/admin/create_article',				['as'=>'create_article', 'uses'=>'ArticleController@create_article', 'middleware'=>'auth']); // redirect
Route::get('/admin/articles', 						['as'=>'admin_articles', 'uses'=>'ArticleController@admin_articles', 'middleware'=>'auth']); // articles (with delete form + change link)
Route::get('/articles', 							['as'=>'articles', 		 'uses'=>'ArticleController@articles']); // articles
Route::get('/articles/{article}/{article_id}', 		['as'=>'article', 		 'uses'=>'ArticleController@article']); // article
Route::post('/admin/change_article/{article_id}', 	['as'=>'change_article', 'uses'=>'ArticleController@change_article', 'middleware'=>'auth']); // article_change (links)
Route::post('/admin/update_article', 				['as'=>'update_article', 'uses'=>'ArticleController@update_article', 'middleware'=>'auth']); // redirect
Route::post('/admin/delete_article/{article_id}', 	['as'=>'delete_article', 'uses'=>'ArticleController@delete_article', 'middleware'=>'auth']); // redirect->with

//SERVICES
Route::get('/services',                         ['as'=>'services',      'uses'=>'ServiceController@services']);
Route::get('/services/{$service}/{service_id}', ['as'=>'service',       'uses'=>'ServiceController@service']);

//

//INTERACTIONS
Route::get('/admin', 		        ['as'=>'admin', 	        'uses'=>'MainController@admin']); // admin
Route::get('/login', 		        ['as'=>'login', 	        'uses'=>'MainController@login']); // login
Route::post('/logging', 	        ['as'=>'logging', 	        'uses'=>'MainController@logging']); // redirect
Route::post('/logout', 		        ['as'=>'logout', 	        'uses'=>'MainController@logout']); // redirect
Route::post('/feedback',	        ['as'=>'feedback', 	        'uses'=>'MainController@feedback']);
Route::get('/admin/feedback', 		['as'=>'admin_feedback', 	'uses'=>'MainController@admin_feedback']); // admin
Route::get('/admin/subscribers', 	['as'=>'admin_subscribers', 'uses'=>'MainController@admin_subscribers']); // admin




/*------------------------------------------------
| FILTERS
------------------------------------------------*/
function filters() {
   $filter_types = [
        'type'			=> 'type', 	// ['flat', 'cottage', 'parcel', 'commercial']
        'commercial'	=> 'type', 	// ['rent', 'sale']
        'sea_dist'		=> 'range',
        'price'			=> 'range',
        'district_id'	=> 'type', 	// ['1', '2', '3']
        'town_id'		=> 'type', 	// ['1', '2', '3']
        'house_area'	=> 'range', // if (in_array($type, ['flat', 'cottage', 'commercial']))
        'rooms'			=> 'range', // if (in_array($type, ['flat', 'cottage', 'commercial']))
        'yard_area'		=> 'range', // if (in_array($type, ['cottage', 'parcel', 'commercial']))
        'period'		=> 'type',  // if (in_array($commercial, ['rent'])) ['hourly', 'daily', 'monthly']
        'pool'			=> 'check',
        'producer'		=> 'list',  // '[asus;acer;lenovo;toshiba;sony]'
    ];

    $take = 10;
    $sort = 'title';
    $order = 'asc';
    $filters = 'house_area=120;380&district_id=2&town_id=3&pool=bool&producer=[asus;acer;lenovo;toshiba;sony]&price=10000;160000&yard_area=100;800&commercial=rent&period=daily&type=cottage&rooms=3;7';

    // $query = Estate::join('districts', 'estates.district_id', '=', 'districts.district_id')->join('towns', 'towns.town_id', '=', 'districts.town_id'); // get Illuminate\Database\Eloquent\Builder
    $query = Estate::joined(); // get Illuminate\Database\Eloquent\Builder
    $query = apply_filters($query, $filters); // $query = Filter::apply($query, $filters);
    $query = $query->orderBy($sort, $order);
    try {
        $estates = $query->skip($skip)->take($take)->get();
    } catch (Exception $e) {
        return redirect()->back();
    }
}
/*------------------------------------------------
| FRONTEND
------------------------------------------------*/
// href='{{ filter('type', 'cottage') }}' // or Filter::make()
// href='{{ filter('producer', '[asus]') }}'
// href='{{ filter('pool', 'bool') }}'
// href='{{ filter('price', '10000;160000') }}'
/*----------------------------------------------*/
function filter($filter, $value, $filters='') {
    parse_str($filters, $filters);

    // resetting dependencies
    // if ('type'==$filter or 'commercial'==$filter) {
    // 	$filters = reset_dependencies($filters);
    // }

    $type = detect_filter_type($value);

    if ('check'==$type) {
        $is_on = array_key_exists($filter, $filters);
        unset($filters[$filter]);
        $filter_query = implode_assoc('&', $filters);
        if ($is_on) {
            return $filter_query;
        } else {
            return $filter_query."&{$filter}={$value}";
        }
    } else if ('list'==$type) {
        // $filters[$filter] = [asus;acer;lenovo;toshiba;sony];
        // $value 			 = [asus]
        // $result 			 = [acer;lenovo;toshiba;sony]
        $value = trim($value, '[]');
        $old_filter = trim($filters[$filter], '[]');
        $items = explode(';', $old_filter);

        $is_on = in_array($value, $items);
        $clear_filter = array_values(array_diff($items,[$value]));
        $clear_filter_str = implode(';', $clear_filter);

        unset($filters[$filter]);
        $filter_query = implode_assoc('&', $filters);
        if ($is_on) {
            return $filter_query."&{$filter}=[{$clear_filter_str}]";
        } else {
            return $filter_query."&{$filter}=[{$clear_filter_str};{$value}]";
        }
    } else if ('range'==$type) {
        unset($filters[$filter]);
        $filter_query = implode_assoc('&', $filters);
        return $filter_query."&{$filter}={$value}";
    } else if ('type'==$type) {
        $is_on = (array_key_exists($filter, $filters) and ($filters[$filter] == $value)); // important parenthesis
        unset($filters[$filter]);
        $filter_query = implode_assoc('&', $filters);
        if ($is_on) {
            return $filter_query;
        } else {
            return $filter_query."&{$filter}={$value}";
        }
    }
}

function implode_assoc($glue, $array) {
    return implode('&', array_map(function($v, $k) { return $k.'='.$v; }, $array, array_keys($array)));
}


//
///*
//|--------------------------------------------------------------------------
//| Routes File
//|--------------------------------------------------------------------------
//|
//| Here is where you will register all of the routes in an application.
//| It's a breeze. Simply tell Laravel the URIs it should respond to
//| and give it the controller to call when that URI is requested.
//|
//*/
//
//Route::get('/', function () {
//    return view('welcome');
//});
//
///*
//|--------------------------------------------------------------------------
//| Application Routes
//|--------------------------------------------------------------------------
//|
//| This route group applies the "web" middleware group to every route
//| it contains. The "web" middleware group is defined in your HTTP
//| kernel and includes session state, CSRF protection, and more.
//|
//*/
//
//Route::group(['middleware' => ['web']], function () {
//    //
//});
