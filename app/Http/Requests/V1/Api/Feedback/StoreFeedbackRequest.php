<?php
namespace App\Http\Requests\V1\Api\Feedback;

use App\Http\Requests\ApiFormRequest;
use App\Models\Booking;
use App\Models\ServiceReservation;
use Illuminate\Validation\Validator;

class StoreFeedbackRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return auth('guest')->check();
    }

    public function rules(): array
    {
        return [
            'booking_id' => [
                'nullable',
                'exists:bookings,id',
                'required_without:service_reservation_id',
                'unique:feedback,booking_id,NULL,id,deleted_at,NULL', // enforce unique booking feedback
            ],
            'service_reservation_id' => [
                'nullable',
                'exists:service_reservations,id',
                'required_without:booking_id',
                'unique:feedback,service_reservation_id,NULL,id,deleted_at,NULL', // enforce unique service reservation feedback
            ],
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $guestId = auth('guest')->id();
            $hotelId = current_hotel_id();

            if ($this->filled('booking_id')) {
                $booking = Booking::find($this->booking_id);

                if (!$booking) {
                    return;
                }

                if ($booking->guest_id !== $guestId) {
                    $validator->errors()->add('booking_id', __('feedback.validation.booking_not_owned'));
                }

                if ($hotelId && $booking->hotel_id !== $hotelId) {
                    $validator->errors()->add('booking_id', __('feedback.validation.booking_wrong_hotel'));
                }
            }

            if ($this->filled('service_reservation_id')) {
                $reservation = ServiceReservation::with('service')->find($this->service_reservation_id);

                if (!$reservation) {
                    return;
                }

                if ($reservation->guest_id !== $guestId) {
                    $validator->errors()->add('service_reservation_id', __('feedback.validation.service_not_owned'));
                }

                if ($hotelId && $reservation->service->hotel_id !== $hotelId) {
                    $validator->errors()->add('service_reservation_id', __('feedback.validation.service_wrong_hotel'));
                }
            }
        });
    }

    public function messages(): array
    {
        return __('feedback.validation');
    }
}
