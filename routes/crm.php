<?php


use App\Http\Controllers\V1\CRM\Campaign\CampaignController;
use App\Http\Controllers\V1\CRM\ContactInfo\ContactInfoController;
use App\Http\Controllers\V1\CRM\Employee\Auth\EmployeeAuthController;
use App\Http\Controllers\V1\CRM\Guest\GuestController;
use App\Http\Controllers\V1\CRM\Offer\OfferController;
use App\Http\Controllers\V1\CRM\Permission\PermissionController;
use App\Http\Controllers\V1\CRM\RestaurantMenu\RestaurantMenuController;
use App\Http\Controllers\V1\CRM\HotelLocation\HotelLocationController;
use App\Http\Controllers\V1\CRM\Restaurant\RestaurantController;
use App\Http\Controllers\V1\CRM\RoomType\RoomTypeController;
use App\Http\Controllers\V1\CRM\SeasonalPrice\SeasonalPriceController;
use App\Http\Controllers\V1\CRM\ServiceCategory\ServiceCategoryController;
use App\Http\Controllers\V1\CRM\BlackoutDate\BlackoutDateController;
use App\Http\Controllers\V1\CRM\Booking\BookingController;
use App\Http\Controllers\V1\CRM\StaticPage\StaticPageController;
use App\Http\Controllers\V1\CRM\View\ViewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\CRM\Role\RoleController;
use App\Http\Controllers\V1\CRM\Employee\EmployeeController;
use App\Http\Controllers\V1\CRM\Service\ServiceController;
use App\Http\Controllers\V1\CRM\Room\RoomController;
use App\Http\Controllers\V1\CRM\Category\CategoryController;
use App\Http\Controllers\V1\CRM\Countries\CountriesController;
use App\Http\Controllers\V1\CRM\Dashboard\DashboardController;
use App\Http\Controllers\V1\CRM\Feature\FeatureController;
use App\Http\Controllers\V1\CRM\Feedback\FeedbackController;
use App\Http\Controllers\V1\CRM\LiveStyleImage\LiveStyleImageController;
use App\Http\Controllers\V1\CRM\Loyalty\Tier\TierController;
use App\Http\Controllers\V1\CRM\ServiceReservation\ServiceReservationController;
use App\Http\Controllers\V1\CRM\Logs\LogsController;
use App\Http\Controllers\V1\CRM\Loyalty\Reward\RewardController;
use App\Http\Controllers\V1\CRM\Loyalty\Point\PointController;
use App\Http\Middleware\CheckLanguage;
use App\Http\Controllers\V1\CRM\Survey\SurveyController;
use App\Http\Controllers\V1\CRM\Loyalty\LoyaltyAccount\LoyaltyAccountController;
use App\Models\Reward;
use App\Models\Tier;

Route::prefix('v1')->group(function () {



    Route::middleware(['auth:employee', 'role:admin', 'hotel.context', CheckLanguage::class])->group(function () {

        Route::prefix('employee')->group(function () {
            Route::get('/all', [EmployeeController::class, 'showAllEmployee']);
            Route::get('/{id}', [EmployeeController::class, 'showEmployee'])->where('id', '[0-9]+');
            Route::post('/add', [EmployeeController::class, 'addEmployee']);
            Route::put('/update/{id}', [EmployeeController::class, 'updateEmployee']);
            Route::delete('/delete/{id}', [EmployeeController::class, 'deleteEmployee']);
        });

        // Route::prefix('service')->group(function () {
        //     Route::post('/add', [ServiceController::class, 'addService']);
        //     Route::get('/all', [ServiceController::class, 'showServices']);
        // });

        // Route::prefix('room')->group(function () {
        //     // Rooms
        //     Route::post('/add', [RoomController::class, 'addRoom']);
        //     Route::get('/all', [RoomController::class, 'showRooms']);
        // });

        Route::prefix('category')->group(function () {
            // Categories
            Route::post('/add', [CategoryController::class, 'addCategory']);
            Route::get('/all', [CategoryController::class, 'showAllCategories']);
            Route::get('/{id}', [CategoryController::class, 'showCategory'])->where('id', '[0-9]+');
            Route::post('/update/{id}', [CategoryController::class, 'updateCategory']);
            Route::delete('/delete/{id}', [CategoryController::class, 'deleteCategory']);
        });

        Route::prefix('beds')->group(function () {
            Route::get('/', [CategoryController::class, 'listBeds']);
        });

        Route::prefix('properties')->group(function () {
            Route::post('/', [HotelLocationController::class, 'store']);
            Route::get('/', [HotelLocationController::class, 'index']);
            Route::post('/{id}', [HotelLocationController::class, 'update']);
            Route::get('/{id}', [HotelLocationController::class, 'show']);
            Route::delete('/{id}', [HotelLocationController::class, 'delete']);
        });

        // Route::prefix('feature')->group(function(){
        //     // Features
        //     Route::post('/add', [FeatureController::class, 'addFeature']);
        //     Route::get('/all', [FeatureController::class, 'showFeatures']);
        // });

        Route::prefix('permission')->group(function () {
            Route::get('/', [PermissionController::class, 'index']);
            Route::post('/', [PermissionController::class, 'store']);
            Route::post('/{id}', [PermissionController::class, 'update']);
            Route::delete('/{id}', [PermissionController::class, 'destroy']);
        });

        Route::prefix('role')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'store']);
            Route::post('/{id}', [RoleController::class, 'update']);
            Route::delete('/{id}', [RoleController::class, 'destroy']);
        });

        Route::prefix('static-page')->group(function () {
            Route::get('/', [StaticPageController::class, 'index']);
            Route::get('/{slug}', [StaticPageController::class, 'show']);
            Route::post('/{slug}', [StaticPageController::class, 'update']);
        });

        Route::prefix('feature')->group(function () {
            Route::get('/', [FeatureController::class, 'index']);
            // Route::get('/unpaginated', [FeatureController::class, 'indexUnpaginated']);
            Route::post('/', [FeatureController::class, 'store']);
            Route::get('/{id}', [FeatureController::class, 'show']);
            Route::post('/{id}', [FeatureController::class, 'update']);
            Route::delete('/{id}', [FeatureController::class, 'destroy']);
        });

        Route::prefix('live_style_image')->group(function () {
            // LiveStyleImages
            Route::post('/add', [LiveStyleImageController::class, 'addLiveStyleImage']);
            Route::get('/all', [LiveStyleImageController::class, 'showAllLiveStyleImages']);
            Route::get('/{id}', [LiveStyleImageController::class, 'showLiveStyleImage'])->where('id', '[0-9]+');
            Route::put('/update/{id}', [LiveStyleImageController::class, 'updateLiveStyleImage']);
            Route::delete('/delete/{id}', [LiveStyleImageController::class, 'deleteLiveStyleImage']);
        });

        Route::prefix('restaurants/menus')->group(function () {
            Route::get('/', [RestaurantMenuController::class, 'getAllMenus']);
            Route::post('/import', [RestaurantMenuController::class, 'import']);
            Route::get('/{restaurant_id}', [RestaurantMenuController::class, 'getRestaurantMenu']);
            Route::delete('/{restaurant_id}', [RestaurantMenuController::class, 'deleteMenu']);
            Route::post('/{id}', [RestaurantMenuController::class, 'updateMenuItem']);
        });

        Route::prefix('restaurant')->group(function () {
            Route::get('/', [RestaurantController::class, 'index']);
            // Route::get('/unpaginated', [RestaurantController::class, 'indexUnPaginated']);
            Route::post('/', [RestaurantController::class, 'store']);
            Route::get('{id}', [RestaurantController::class, 'show']);
            Route::post('{id}', [RestaurantController::class, 'update']);
            Route::delete('{id}', [RestaurantController::class, 'destroy']);
            Route::post('/availability/{id}', [RestaurantController::class, 'availability']);
            Route::get('/reservations/{id}', [RestaurantController::class, 'getRestaurantReservations']);
        });

        Route::prefix('service-categories')->group(function () {
            Route::post('/', [ServiceCategoryController::class, 'store']);
            Route::get('/', [ServiceCategoryController::class, 'index']);
            Route::get('/{id}', [ServiceCategoryController::class, 'show']);
            Route::put('/{id}', [ServiceCategoryController::class, 'update']);
            Route::delete('/{id}', [ServiceCategoryController::class, 'destroy']);
        });

        Route::prefix('service-type')->group(function () {
            Route::post('/', [ServiceController::class, 'store']);
            Route::get('/', [ServiceController::class, 'index']);
            Route::post('/{id}', [ServiceController::class, 'update']);
            Route::get('/{id}', [ServiceController::class, 'show']);
            Route::delete('/{id}', [ServiceController::class, 'destroy']);
            Route::post('availability/{id}', [ServiceController::class, 'setAvailability']);
        });

        Route::prefix('blackout-date')->group(function () {
            // BlackoutDates
            Route::post('/add', [BlackoutDateController::class, 'addBlackoutDate']);
            Route::get('/all', [BlackoutDateController::class, 'showAllBlackoutDates']);
            Route::get('/{id}', [BlackoutDateController::class, 'showBlackoutDate'])->where('id', '[0-9]+');
            Route::post('/update/{id}', [BlackoutDateController::class, 'updateBlackoutDate']);
            Route::delete('/delete/{id}', [BlackoutDateController::class, 'deleteBlackoutDate']);
        });

        Route::prefix('guests')->group(function () {
            Route::get('/', [GuestController::class, 'index']);
            Route::get('/export', [GuestController::class, 'export']);
            Route::get('/list-names', [GuestController::class, 'listNames']);
            Route::get('/{id}', [GuestController::class, 'get']);
        });

        Route::prefix('views')->group(function () {
            Route::get('/', [ViewController::class, 'index']);
        });

        Route::prefix('countries')->group(function () {
            Route::get('/', [CountriesController::class, 'index']);
        });

        Route::prefix('room-types')->group(function () {
            Route::get('/', [RoomTypeController::class, 'index']);
            // Route::get('/unpaginated', [RoomTypeController::class, 'indexUnpaginated']);
            Route::post('/', [RoomTypeController::class, 'store']);
            Route::get('/{id}', [RoomTypeController::class, 'show']);
            Route::post('/{id}', [RoomTypeController::class, 'update']);
            Route::delete('/{id}', [RoomTypeController::class, 'destroy']);
            Route::get('/{id}/seasonal-prices', [SeasonalPriceController::class, 'indexByRoomType']);
        });

        Route::prefix('rooms')->group(function () {
            Route::get('/', [RoomController::class, 'index']);
            // Route::get('/unpaginated', [RoomController::class, 'indexUnpaginated']);
            Route::get('/{id}', [RoomController::class, 'show']);
            Route::post('/', [RoomController::class, 'store']);
            Route::post('/bulk', [RoomController::class, 'bulkStore']);
            Route::post('/{id}', [RoomController::class, 'update']);
            Route::delete('/{id}', [RoomController::class, 'destroy']);
        });

        Route::prefix('seasonal-prices')->group(function () {
            Route::get('/{id}', [SeasonalPriceController::class, 'show']);
            Route::post('/', [SeasonalPriceController::class, 'store']);
            Route::post('/{id}', [SeasonalPriceController::class, 'update']);
            Route::delete('/{id}', [SeasonalPriceController::class, 'destroy']);
        });

        Route::prefix('contact-infos')->group(function () {
            Route::get('/', [ContactInfoController::class, 'index']);
            Route::get('/{id}', [ContactInfoController::class, 'show']);
            Route::post('/', [ContactInfoController::class, 'store']);
            Route::post('/{id}', [ContactInfoController::class, 'update']);
            Route::delete('/{id}', [ContactInfoController::class, 'destroy']);
        });

        Route::prefix('offers')->group(function () {
            Route::post('/', [OfferController::class, 'store']);
            Route::put('/{id}', [OfferController::class, 'update']);
            Route::get('/', [OfferController::class, 'index']);
            Route::get('/{id}', [OfferController::class, 'show']);
            Route::delete('/{id}', [OfferController::class, 'destroy']);
        });

        Route::prefix('loyalty')->group(function () {

            Route::prefix('tier')->controller(TierController::class)->group(function () {
                Route::get('/all', 'showAllTiers');
                Route::get('/{id}', 'showTier')->where('id', '[0-9]+');
                Route::post('/add', 'addTier');
                Route::put('/update/{id}', 'updateTier');
                Route::delete('/delete/{id}', 'deleteTier');
            });
            Route::prefix('loyaltyAccount')->group(function () {
                Route::get('/{id}', [LoyaltyAccountController::class, 'showLoyaltyAccount'])->where('id', '[0-9]+');
                Route::get('/all', [LoyaltyAccountController::class, 'showAllAccounts']);
                Route::post('/add', [LoyaltyAccountController::class, 'addAccount']);
                Route::put('/update/{id}', [LoyaltyAccountController::class, 'updateAccount']);
                Route::delete('/delete/{id}', [LoyaltyAccountController::class, 'destroyAccount']);
            });

            Route::prefix('reward')->group(function () {
                Route::get('/{id}', [RewardController::class, 'show'])->where('id', '[0-9]+');
                Route::get('/all', [RewardController::class, 'index']);
                Route::post('/add', [RewardController::class, 'store']);
                Route::put('/update/{id}', [RewardController::class, 'update']);
                Route::delete('/delete/{id}', [RewardController::class, 'destroy']);
            });
        });

        Route::prefix('logs')->group(function () {
            Route::get('/', [LogsController::class, 'index']);
        });

        Route::prefix('service-reservations')->group(function () {
            Route::post('/', [ServiceReservationController::class, 'store']);
            Route::post('/{id}', [ServiceReservationController::class, 'update']);
            Route::get('/', [ServiceReservationController::class, 'index']);
            Route::get('/{serviceReservationId}/feedback', [FeedbackController::class, 'getFeedbackByServiceReservation']);
            Route::get('/{id}', [ServiceReservationController::class, 'show']);
            Route::get('/guests/by-service-category/{serviceCategoryId}', [ServiceReservationController::class, 'getGuestsByServiceCategory']);
        });

        Route::prefix('feedback')->group(function () {
            Route::get('/', [FeedbackController::class, 'index']);
            Route::get('{id}', [FeedbackController::class, 'show']);
        });

        Route::prefix('booking')->group(function () {
            Route::get('/{bookingId}/feedback', [FeedbackController::class, 'getFeedbackByBooking']);
            Route::get('/guest/{guestId}/total-nights', [BookingController::class, 'getTotalNightsByGuest']);
            Route::post('/create-for-guest', [BookingController::class, 'createBookingForGuest']);
            Route::get('/', [BookingController::class, 'index']);
        });
        Route::prefix('survey')->group(function () {
            Route::get('/{id}', [SurveyController::class, 'show'])->where('id', '[0-9]+');
            Route::get('/all', [SurveyController::class, 'index']);
            Route::post('/add', [SurveyController::class, 'store']);
            Route::put('/update/{id}', [SurveyController::class, 'update']);
            Route::delete('/delete/{id}', [SurveyController::class, 'destroy']);
        });
        Route::prefix('dashboard')->group(function () {
            Route::get('/overview', [DashboardController::class, 'overview']);
        });
    });

    Route::middleware(['auth:employee', 'role:marketing|admin', CheckLanguage::class])->group(function () {
        Route::prefix('campaigns')->group(function () {
            Route::post('/store', [CampaignController::class, 'store']);
            Route::get('/index', [CampaignController::class, 'index']);
            Route::get('/{id}', [CampaignController::class, 'show']);
            Route::post('/update/{id}', [CampaignController::class, 'update']);
            Route::post('/approve/{id}', [CampaignController::class, 'approve']);
            Route::post('/archive/{id}', [CampaignController::class, 'archive']);
            Route::delete('/{id}', [CampaignController::class, 'destroy']);
        });
    });
    Route::post('/employee/login', [EmployeeAuthController::class, 'login'])->name('login');
    Route::post('/employee/logout', [EmployeeAuthController::class, 'logout']);
});
