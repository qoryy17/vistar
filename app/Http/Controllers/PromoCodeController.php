<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PromoCodeController extends Controller
{
    public static function checkPromoCode(string $promoCode)
    {
        $promoCodeType = null;
        $promoType = null;
        $promoValue = null;

        $userMitra = UserMitra::select('id', 'buyer_benefit_type', 'buyer_benefit_value')
            ->where('promotion_code', $promoCode)
            ->first();
        if ($userMitra) {
            $promoCodeType = 'mitra';
            $promoType = $userMitra->buyer_benefit_type;
            $promoValue = $userMitra->buyer_benefit_value;
        }

        if (!$promoType || !$promoValue) {
            $user = User::select('id')->where('kode_referral', $promoCode)->first();
            if (!$user) {
                return ['result' => 'error', 'title' => 'Kode Promo tidak ditemukan.'];
            }

            $promoCodeType = 'referral';

            /* NOTE: Block access referral code at the moment  */
            return ['result' => 'error', 'title' => 'Kode Referral belum dapat digunakan.'];
        }

        if (!$promoType || !$promoValue) {
            return ['result' => 'error', 'title' => 'Promo tidak diketahui.'];
        }

        return [
            'result' => 'success',
            'title' => 'Kode Promo valid.',
            'data' => [
                'code' => $promoCode,
                'type' => $promoCodeType,
                'promo' => [
                    'type' => $promoType,
                    'value' => $promoValue,
                ],
            ],
        ];
    }

    public function check(Request $request)
    {
        $promoCode = $request->promo_code;

        return response()->json(PromoCodeController::checkPromoCode($promoCode));
    }

    public function apply(Request $request)
    {
        $allowedTypes = ['mitra', 'referral'];
        $type = $request->type;
        $promoCode = $request->promoCode;

        if (!in_array($type, $allowedTypes)) {
            return redirect()->back()->with('error', 'Tipe Promo tidak dikenali.');
        }

        $promoType = null;
        $promoValue = null;

        if ($type === 'mitra') {
            $userMitra = UserMitra::select('id', 'buyer_benefit_type', 'buyer_benefit_value')
                ->where('promotion_code', $promoCode)
                ->first();
            if (!$userMitra) {
                return redirect()->back()->with('error', 'Promo tidak ada.');
            }

            $promoType = $userMitra->buyer_benefit_type;
            $promoValue = $userMitra->buyer_benefit_value;
        } elseif ($type === 'referral') {
            /* IDEA: Get referral based on user data */
        }

        if (!$promoType || !$promoValue) {
            return redirect()->back()->with('error', 'Promo tidak diketahui.');
        }

        $this->setCookie($promoCode, $promoType, $promoValue);

        return redirect()->route('mainweb.product')->with('success', 'Promo berhasil diaktifkan.');
    }

    public function setCookie($promoCode, $promoType, $promoValue)
    {
        $cookieDurations = 31 * 24 * 60 * 60;

        Cookie::queue('promoCode', $promoCode, $cookieDurations);
        Cookie::queue('promoType', $promoType, $cookieDurations);
        Cookie::queue('promoValue', $promoValue, $cookieDurations);
    }

    public static function deleteCookie()
    {
        Cookie::queue('promoCode', null, -1);
        Cookie::queue('promoType', null, -1);
        Cookie::queue('promoValue', null, -1);
    }

    public static function getPromoCode()
    {
        $promoCode = Cookie::get('promoCode');
        $promoType = Cookie::get('promoType');
        $promoValue = intval(Cookie::get('promoValue'));

        if (!$promoCode || !$promoType || !$promoValue) {
            return null;
        }

        return [
            'code' => $promoCode,
            'promo' => [
                'type' => $promoType,
                'value' => $promoValue,
            ],
        ];
    }
}
