<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Country;
use App\Models\City;
class Guest extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'guest_title',
        'first_name',
        'last_name',
        'passport_no',
        'passport_or_id_flag',
        'passport_or_id_num',
        'email',
        'password',
        'phone_no',
        'is_loyalty_member',
        'member_since',
        'loyalty_tier',
        'total_points',
        'is_verified',
        'status',
        'country_id',
        'city_id',
        'profile_photo'
    ];

    protected $dates = ['deleted_at'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function serviceReservations()
    {
        return $this->hasMany(ServiceReservation::class, 'guest_id');
    }

    public function tier()
    {
        return $this->belongsTo(Tier::class, 'tier_id');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'guest_id');
    }
    public function surveyAnswers()
    {
        return $this->hasMany(SurveyAnswer::class, 'guest_id');
    }
    public function surveys()
    {
        return $this->belongsToMany(Survey::class, 'survey_answers')
                    ->withPivot(['text_answer', 'selected_option', 'rating_answer'])
                    ->withTimestamps();
    }
    public function loyaltyAccount()
    {
        return $this->hasOne(LoyaltyAccount::class, 'user_id');
    }
    protected $hidden = ['password'];
}
