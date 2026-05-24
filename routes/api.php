<?php

use App\Http\Controllers\V1\Api\ServiceReservation\ServiceReservationController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\Api\V1\CheckTokenExpiration;
use App\Http\Controllers\V1\Api\Room\RoomController;

use App\Http\Controllers\V1\Api\{
    CategoryController,
    FeatureController,
    // ServiceController,
    RestaurantController,
    LiveStyleImageController,
    CountriesWithCitiesController,
    HomeController
};
use App\Http\Controllers\V1\Api\StaticPages\StaticPagesController;
use App\Http\Controllers\V1\Api\StaticPages\{
    //     AboutController,
    //     TermsAndConditionController,
    ContactNumbersController,
    //     PrivacyAndPoliceController,
    LocationPermissionController
};

use App\Http\Controllers\V1\Api\Auth\AuthGuestController;
use App\Http\Controllers\V1\Api\Booking\BookingController;
use App\Http\Controllers\V1\Api\Feedback\FeedbackController;
use App\Http\Controllers\V1\Api\Guest\GuestProfileController;
use App\Http\Controllers\V1\Api\HotelLocation\HotelLocationController;
use App\Http\Controllers\V1\Api\Service\ServiceController;
use App\Http\Controllers\V1\Api\Notification\NotificationController;
use App\Http\Controllers\V1\Api\Loyalty\LoyaltyController;
use App\Http\Middleware\CheckLanguage;
use App\Models\Feedback;
use App\Models\HotelLocation;

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to the Mobile API']);
});

Route::prefix('v1')->middleware(CheckLanguage::class)->group(function () {
    // Public Routes
    Route::post('/login', [AuthGuestController::class, 'login']);
    Route::post('/register', [AuthGuestController::class, 'register']);
    Route::post('/verify-otp', [AuthGuestController::class, 'verifyOTP']);
    Route::post('/resend-otp', [AuthGuestController::class, 'resendOTP']);
    Route::post('/reset-password', [AuthGuestController::class, 'resetPassword']);
    Route::post('/request-reset-password', [AuthGuestController::class, 'requestResetPassword']);

    // Static Pages

    Route::get('about', [StaticPagesController::class, 'showAbout']);
    Route::get('contact-numbers', [ContactNumbersController::class, 'showContactNumbers']);
    Route::get('terms_condition', [StaticPagesController::class, 'ShowTermsAndCondition']);
    Route::get('privacy_policy', [StaticPagesController::class, 'showPrivacyAndPolice']);
    Route::get('location_permission', [LocationPermissionController::class, 'showLocationPermission']);
    Route::get('hotels', [HotelLocationController::class, 'index']);


    // Restaurants
    Route::post('restaurant/add', [RestaurantController::class, 'addRestaurant']);
    Route::get('restaurants', [RestaurantController::class, 'showRestaurant']);
    Route::get('restaurants/{id}/menu', [RestaurantController::class, 'getRestaurantMenu']);

    // Life Style Images
    Route::post('life_style_image/add', [LiveStyleImageController::class, 'addLiveStyleImage']);
    Route::get('life_style_images', [LiveStyleImageController::class, 'showLiveStyleImage']);

    Route::get('/country_city', [CountriesWithCitiesController::class, 'countriesWithCities']);
    Route::get('/home', [HomeController::class, 'home']);
    Route::prefix('guest')->middleware(['auth:sanctum', CheckTokenExpiration::class])
        ->group(function () {
            Route::get('/profile/show', [GuestProfileController::class, 'show']);
            Route::put('/profile/update', [GuestProfileController::class, 'update']);
            Route::post('/logout', [AuthGuestController::class, 'logout']);
        });

    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'index']);
    });

    Route::prefix('notifications')->group(function () {
        Route::post('/send', [NotificationController::class, 'send']);
        Route::get('/', [NotificationController::class, 'index']);
    });

    Route::middleware('hotel.context')->group(function () {
        Route::post('/booking_room', [RoomController::class, 'bookRoom']);
        Route::get('/room-filters', [RoomController::class, 'getRoomFilters']);
        Route::get('/check_available_rooms', [RoomController::class, 'showRoom']);
    });


    // Protected Guest Routes

    Route::prefix('guest')->middleware(['auth:sanctum', CheckTokenExpiration::class])->group(function () {
        Route::get('/profile/show', [GuestProfileController::class, 'show']);
        Route::put('/profile/update', [GuestProfileController::class, 'update']);
        Route::post('/logout', [AuthGuestController::class, 'logout']);
    });

    Route::middleware('auth:guest', 'hotel.context')->group(function () {
        Route::prefix('service-reservations')->group(function () {
            Route::post('/', [ServiceReservationController::class, 'store']);
            Route::post('/{id}', [ServiceReservationController::class, 'update']);
            Route::get('/', [ServiceReservationController::class, 'indexForGuest']);
            Route::get('/{serviceReservationId}/feedback', [FeedbackController::class, 'getFeedbackByServiceReservation']);
            Route::get('/{id}', [ServiceReservationController::class, 'showForGuest']);
        });

        Route::prefix('restaurants')->group(function () {
            Route::post('/reserve', [RestaurantController::class, 'reserve']);
            Route::get('/reservations/guest', [RestaurantController::class, 'getRestaurantReservationsforGuest']);
        });

        Route::prefix('loyalty')->group(function () {
            Route::post('/assign-to-loyalty-account', [LoyaltyController::class, 'assignGuestToLoyaltyAccount']);
            // Route::post('/transaction', [LoyaltyController::class, 'loyaltyTransaction']);
            Route::post('/redeem-reward', [LoyaltyController::class, 'redeemReward']);
        });

        Route::prefix('feedback')->group(function () {
            Route::post('/', [FeedbackController::class, 'store']);
            Route::post('{id}', [FeedbackController::class, 'update']);
            Route::get('/', [FeedbackController::class, 'index']);
            Route::get('{id}', [FeedbackController::class, 'show']);
        });

        Route::prefix('booking')->group(function () {
            Route::get('/{bookingId}/feedback', [FeedbackController::class, 'getFeedbackByBooking']);
            Route::get('/history', [BookingController::class, 'index']);
        });
    });
});
