<?php

declare(strict_types=1);

namespace App\Cmf\Project;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\PaymentCharge;
use App\Models\Reservation;
use App\Models\Transfer;
use App\Models\User;
use App\Services\Calendar\UserCalendarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return View
     */
    public function index()
    {

        $data['payments'] = $this->getDataForPayments();
        $data['reservations'] = $this->getDataForReservations();
        $data['users'] = $this->getUsers();
        $data['listings'] = $this->getListings();

        //return view('cmf.dashboard');
        return view('cmf.statistic', [
            'data' => $data,
        ]);
    }

    /**
     * @return array
     */
    private function getDataForPayments()
    {
        // за весь
        $amount = Payment::active()
            ->sum('amount');
        $service_fee = Payment::active()
            ->sum('service_fee');
        $count = Payment::active()
            ->count();
        $dept = $amount - $service_fee;

        $data['payments']['all']['count'] = $count;
        $data['payments']['all']['amount'] = number_format($amount, 2);
        $data['payments']['all']['service_fee'] = number_format($service_fee, 2);
        $data['payments']['all']['dept'] = number_format($dept, 2);

        // за весь
        $amount = PaymentCharge::active()
            ->sum('amount');
        $count = PaymentCharge::active()
            ->count();
        $data['payments']['charges']['count'] = $count;
        $data['payments']['charges']['value'] = number_format($amount, 2);

        return $data['payments'];
    }

    /**
     * @return array
     */
    private function getDataForReservations()
    {
        $future = Reservation::futureNotBeginning()
            ->count();

        $futureToday = Reservation::futureNotBeginning()
            ->whereBetween('server_start_at', [
                now()->startOfDay(),
                now()->endOfDay(),
            ])
            ->count();
        $passed = Reservation::passed()
            ->count();
        $cancelled = Reservation::cancelled()
            ->count();
        // за весь
        $all = Reservation::active()
            ->count();
        // за весь
        $beginning = Reservation::beginning()
            ->count();

        $currentTransfersAmount = 0;
        /** @var Reservation[] $oReservationsActiveWithTransfers */
        $oReservationsActiveWithTransfers = Reservation::beginning()
            ->whereNotNull('transfer_id')
            ->whereNull('payout_id')
            ->get();
        foreach ($oReservationsActiveWithTransfers as $oReservationsActiveWithTransfer) {
            $currentTransfersAmount = $currentTransfersAmount + $oReservationsActiveWithTransfer->transfer->amount;
        }

        $passedPayoutsAmount = 0;
        /** @var Reservation[] $oReservationsPassedPayouts */
        $oReservationsPassedPayouts = Reservation::passed()
            ->whereNotNull('transfer_id')
            ->whereNotNull('payout_id')
            ->get();
        foreach ($oReservationsPassedPayouts as $oReservationsPassedPayout) {
            $passedPayoutsAmount = $passedPayoutsAmount + $oReservationsPassedPayout->payout->amount;
        }

        return [
            'future' => $future,
            'passed' => $passed,
            'cancelled' => $cancelled,
            'beginning' => $beginning,
            'future_today' => $futureToday,
            'beginning_transfers_amount' => number_format($currentTransfersAmount, 2),
            'passed_payouts_amount' => number_format($passedPayoutsAmount, 2),
            'all' => $all,
        ];
    }

    /**
     * @return array
     */
    private function getUsers()
    {
        $all = User::count();
        $active = User::active()->count();
        $banned = User::whereNotNull('banned_at')->count();
        $newPerWeek = User::active()->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count();

        return [
            'new' => $newPerWeek,
            'banned' => $banned,
            'active' => $active,
            'all' => $all,
        ];
    }

    /**
     * @return array
     */
    private function getListings()
    {
        $all = Listing::count();
        $active = Listing::active()->count();
        $banned = Listing::whereNotNull('banned_at')->count();
        $newPerWeek = Listing::active()->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count();

        return [
            'new' => $newPerWeek,
            'banned' => $banned,
            'active' => $active,
            'all' => $all,
        ];
    }

    /**
     * @param string $name
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function unknown(string $name)
    {
        return view('cmf.unknown');
    }
}
