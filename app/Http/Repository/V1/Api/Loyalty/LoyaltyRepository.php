<?php

namespace App\Http\Repository\V1\Api\Loyalty;

use App\Models\Guest;
use App\Models\HotelLocation;

use Illuminate\Http\Request;
use App\Utils\FileUpload;

use Illuminate\Support\Facades\DB;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\LoyaltyRedemption;
use Exception;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class LoyaltyRepository
{

    public function addGuestToLoyaltyAccountRepository($guestId,$tier_id, $pointEarned, $pointRedeemed)
    {
        try {
            DB::beginTransaction();
            //check if guest exists
            $guest = Guest::find($guestId);
            if (!$guest) {
                return [
                    'status' => 'error',
                    'message' => "guest_not_found",
                ];
            }
            // Check if loyalty account already exists
            $loyaltyAccount = LoyaltyAccount::where('user_id', $guestId)->first();

            if ($loyaltyAccount) {
                // Update existing account
                // $loyaltyAccount->lifetime_earned += $pointEarned;
                // $loyaltyAccount->lifetime_redeemed += $pointRedeemed;

                // // Recalculate balance
                // $loyaltyAccount->balance = $loyaltyAccount->lifetime_earned - $loyaltyAccount->lifetime_redeemed;
                // $loyaltyAccount->save();

                // DB::commit();

                return [
                    'status' => 'success',
                    'message' => __('loyalty.account_already_exists'),
                    'data' => $loyaltyAccount,
                ];
            }

            // Create new account if not exists
            $newAccount = LoyaltyAccount::create([
                'user_id' => $guestId,
                'tier_id' => $request->tier_id ?? null,
                'balance' => $pointEarned - $pointRedeemed,
                'lifetime_earned' => $pointEarned,
                'lifetime_redeemed' => $pointRedeemed,
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => __('loyalty.guest_assign_guest_accout_successfully'),
                'data' => $newAccount,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    public function earnPointRepository($guestId,$tier_id, $pointEarned, $pointRedeemed)
    {
        try {
            DB::beginTransaction();
            //check if guest exists
            $guest = Guest::find($guestId);
            if (!$guest) {
                return [
                    'status' => 'error',
                    'message' => "guest_not_found",
                ];
            }
            // Check if loyalty account already exists
            $loyaltyAccount = LoyaltyAccount::where('user_id', $guestId)->first();

            if ($loyaltyAccount) {
                // Update existing account
                $loyaltyAccount->lifetime_earned += $pointEarned;
                $loyaltyAccount->lifetime_redeemed += $pointRedeemed;

                // Recalculate balance
                $loyaltyAccount->balance = $loyaltyAccount->lifetime_earned - $loyaltyAccount->lifetime_redeemed;
                $loyaltyAccount->save();

                DB::commit();

                return [
                    'status' => 'success',
                    'message' => __('loyalty.points_earned_successfully'),
                    'data' => $loyaltyAccount,
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => "loyalty_account_not_found",
                ];
            }

        } catch (Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function addTransaction($dto)
    {
        try {
            // Start DB transaction
            DB::beginTransaction();

            $account = LoyaltyAccount::find($dto->account_id);

            if (!$account) {
                throw new Exception(__('loyalty/transaction.account_not_found'));
            }
            // Handle the transaction logic

            if ($dto->type === 'redeem') {
                if ($account->balance < $dto->points_change) {
                    return [
                        'status' => 'error',
                        'message' => __('loyalty/transaction.insufficient_points'),
                    ];
                }

                $account->lifetime_redeemed += $dto->points_change;
                $account->balance -= $dto->points_change;


            } elseif ($dto->type === 'earn') {
                $account->lifetime_earned += $dto->points_change;
                $account->balance += $dto->points_change;
            } else {
                DB::rollBack();
                return [
                    'status' => 'error',
                    'message' => __('loyalty/transaction.invalid_transaction_type'),
                ];
            }
            // Save account changes
            if (!$account->save()) {
                return [
                    'status' => 'error',
                    'message' => __('loyalty/transaction.account_save_failed'),
                ];
            }

            // Create transaction record
            $transaction = LoyaltyTransaction::create([
                'account_id'     => $account->id,
                'points_change'  => $dto->points_change,
                'balance_after'  => $account->balance,
                'type'           => $dto->type,
                'source'         => $dto->source ?? null,
                'source_id'      => $dto->source_id ?? null,
                'valid_from'     => $dto->valid_from ?? now(),
                'expires_at'     => $dto->expires_at ?? null,
            ]);

            if (!$transaction) {
                return [
                    'status' => 'error',
                    'message' => __('loyalty/transaction.transaction_save_failed'),
                ];
            }

            DB::commit();

            return [
                'status' => 'success',
                'message' => __('loyalty/transaction.transaction_completed_successfully'),
                // 'data' => [
                //     'account' => $account,
                //     'transaction' => $transaction,
                // ],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function redeemReward($dto){
        try {
            // Start DB transaction
            DB::beginTransaction();

            // Create transaction record
            $transactionResult=$this->addTransaction($dto);
            if ($transactionResult['status'] === 'error') {
                return [
                    'status' => 'error',
                    'message' =>$transactionResult['message'],
                ];
            }


            $redeemed = LoyaltyRedemption::create([
                'account_id'     => $dto->account_id,
                'reward_id'      => $dto->reward_id,
                'status'     => $dto->status,
                'idempotency_key'  => $dto->idempotency_key?? null,
                'fulfilled_at'     => $dto->fulfilled_at ?? null,
            ]);
            if (!$redeemed) {
                return [
                    'status' => 'error',
                    'message' => __('loyalty/redeem.redeem_reward_failed'),
                ];
            }
            DB::commit();

            return [
                'status' => 'success',
                'message' => __('loyalty/redeem.redeem_reward_successfully'),
                // 'data' => [
                //     'account' => $account,
                //     'transaction' => $transaction,
                // ],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }

    }
}
