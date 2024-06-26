<?php

use Illuminate\Support\Facades\Route;

Route::impersonate();

Route::get('/', '\Wave\Http\Controllers\HomeController@index')->name('wave.home');
Route::get('@{username}', '\Wave\Http\Controllers\ProfileController@index')->name('wave.profile');

// Documentation routes
Route::view('docs/{page?}', 'docs::index')->where('page', '(.*)');

// Additional Auth Routes
Route::get('logout', '\Wave\Http\Controllers\Auth\LoginController@logout')->name('wave.logout');
Route::get('user/verify/{verification_code}', '\Wave\Http\Controllers\Auth\RegisterController@verify')->name('verify');
Route::post('register/complete', '\Wave\Http\Controllers\Auth\RegisterController@complete')->name('wave.register-complete');

Route::get('blog', '\Wave\Http\Controllers\BlogController@index')->name('wave.blog');
Route::get('blog/{category}', '\Wave\Http\Controllers\BlogController@category')->name('wave.blog.category');
Route::get('blog/{category}/{post}', '\Wave\Http\Controllers\BlogController@post')->name('wave.blog.post');

Route::view('install', 'wave::install')->name('wave.install');

/***** Pages *****/
Route::get('p/{page}', '\Wave\Http\Controllers\PageController@page');

/***** Pricing Page *****/
Route::view('pricing', 'theme::pricing')->name('wave.pricing');

/***** Billing Routes *****/
Route::post('paddle/webhook', '\Wave\Http\Controllers\WebhookController');
Route::post('checkout', '\Wave\Http\Controllers\SubscriptionController@checkout')->name('checkout');

Route::get('test', '\Wave\Http\Controllers\SubscriptionController@test');

Route::group(['middleware' => 'wave'], function () {
	Route::get('dashboard', '\Wave\Http\Controllers\DashboardController@index')->name('wave.dashboard');
});

Route::group(['middleware' => 'auth'], function(){
	Route::get('settings/{section?}', '\Wave\Http\Controllers\SettingsController@index')->name('wave.settings');

	Route::post('settings/profile', '\Wave\Http\Controllers\SettingsController@profilePut')->name('wave.settings.profile.put');
	Route::put('settings/security', '\Wave\Http\Controllers\SettingsController@securityPut')->name('wave.settings.security.put');

	Route::post('settings/api', '\Wave\Http\Controllers\SettingsController@apiPost')->name('wave.settings.api.post');
	Route::put('settings/api/{id?}', '\Wave\Http\Controllers\SettingsController@apiPut')->name('wave.settings.api.put');
	Route::delete('settings/api/{id?}', '\Wave\Http\Controllers\SettingsController@apiDelete')->name('wave.settings.api.delete');

	Route::get('settings/invoices/{invoice}', '\Wave\Http\Controllers\SettingsController@invoice')->name('wave.invoice');

	Route::get('notifications', '\Wave\Http\Controllers\NotificationController@index')->name('wave.notifications');
	Route::get('announcements', '\Wave\Http\Controllers\AnnouncementController@index')->name('wave.announcements');
	Route::get('announcement/{id}', '\Wave\Http\Controllers\AnnouncementController@announcement')->name('wave.announcement');
	Route::post('announcements/read', '\Wave\Http\Controllers\AnnouncementController@read')->name('wave.announcements.read');
	Route::get('notifications', '\Wave\Http\Controllers\NotificationController@index')->name('wave.notifications');
	Route::post('notification/read/{id}', '\Wave\Http\Controllers\NotificationController@delete')->name('wave.notification.read');

    /********** Checkout/Billing Routes ***********/
    Route::post('cancel', '\Wave\Http\Controllers\SubscriptionController@cancel')->name('wave.cancel');
    Route::view('checkout/welcome', 'theme::welcome');

    Route::post('subscribe', '\Wave\Http\Controllers\SubscriptionController@subscribe')->name('wave.subscribe');
	Route::view('trial_over', 'theme::trial_over')->name('wave.trial_over');
	Route::view('cancelled', 'theme::cancelled')->name('wave.cancelled');
    Route::post('switch-plans', '\Wave\Http\Controllers\SubscriptionController@switchPlans')->name('wave.switch-plans');

	//Communication Module
	Route::get('admin/communications', '\Wave\Http\Controllers\CommunicationController@index')->name('wave.communications');
	Route::post('admin/update-create-connections', '\Wave\Http\Controllers\CommunicationController@create_update_connections')->name('update.create.connections');
	Route::post('admin/change-connection-status', '\Wave\Http\Controllers\CommunicationController@change_connection_status');

	//Customer Only
	Route::group(['middleware' => 'customer'], function(){
		Route::get('customer/dashboard', [Wave\Http\Controllers\Customer\DashboardController::class, 'dashboard']);
		Route::get('customer/sale-overview', [Wave\Http\Controllers\Customer\DashboardController::class, 'sale_overview']);
		Route::get('customer/product-listing', [Wave\Http\Controllers\Customer\DashboardController::class, 'product_listing']);
		Route::get('customer/products', [Wave\Http\Controllers\Customer\DashboardController::class, 'products']);
		Route::get('customer/manage-listing', [Wave\Http\Controllers\Customer\DashboardController::class, 'manage_listing']);
		Route::get('customer/manage-rules', [Wave\Http\Controllers\Customer\DashboardController::class, 'manage_rules']);
		Route::get('customer/product-images', [Wave\Http\Controllers\Customer\DashboardController::class, 'product_images']);
		Route::get('customer/product-data', [Wave\Http\Controllers\Customer\DashboardController::class, 'product_data']);
		Route::get('customer/my-account', [Wave\Http\Controllers\Customer\ProfileController::class, 'my_account']);
		Route::post('customer/update-email', [Wave\Http\Controllers\Customer\ProfileController::class, 'update_profile_email'])->name('update.email');
		Route::post('customer/update-password', [Wave\Http\Controllers\Customer\ProfileController::class, 'update_profile_password'])->name('update.password');
		Route::post('customer/submit-profile-details', [Wave\Http\Controllers\Customer\ProfileController::class, 'submit_profile_details'])->name('submit.profile.details');
		Route::post('customer/submit-delete-my-account', [Wave\Http\Controllers\Customer\ProfileController::class, 'submit_delete_my_account'])->name('submit.dlete.my.account');
		Route::post('customer/submit-ebay-connection', [Wave\Http\Controllers\Customer\EbayConnectionController::class, 'submit_ebay_connection'])->name('customer.submit.ebay.connection');;
		Route::get('customer/get-ebay-connection', [Wave\Http\Controllers\Customer\EbayConnectionController::class, 'ebay_authorization_callback']);
		Route::get('customer/synchronise-order-manually', [Wave\Http\Controllers\Customer\ManuallySynchroniseController::class, 'synchronise_order_manually']);
		Route::get('customer/synchronise-listing-manually', [Wave\Http\Controllers\Customer\ManuallySynchroniseController::class, 'synchronise_listing_manually']);
		Route::get('customer/add-new-product', [Wave\Http\Controllers\Customer\ProductController::class, 'add_new_product']);
		Route::post('customer/submit-product', [Wave\Http\Controllers\Customer\ProductController::class, 'submit_product'])->name('customer.submit.product');
	});
});

Route::group(['middleware' => 'admin.user'], function(){
    Route::view('admin/do', 'wave::do');
});