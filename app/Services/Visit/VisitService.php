<?php

declare(strict_types=1);

namespace App\Services\Visit;

use App\Models\Listing;
use App\Models\User;

class VisitService
{
    const NAME_LISTING = 'listing';

    const EXPIRED_MINUTES = 1;

    /**
     * @param Listing|User|mixed $oItem
     * @return int
     */
    public function count($oItem)
    {
        return $oItem->visits()->count();
    }

    /**
     * @param Listing|User|mixed $oItem
     * @return int
     */
    public function countMonthly($oItem)
    {
        return $oItem->visits()->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ])->count();
    }

    /**
     * @param Listing $oItem
     */
    public function listing(Listing $oItem): void
    {
        $this->increment($oItem);
    }

    /**
     * @param User $oItem
     */
    public function user(User $oItem): void
    {
        $this->increment($oItem);
    }

    /**
     * @param Listing|User|mixed $oItem
     * @return mixed
     */
    private function increment($oItem)
    {
        $ip = request()->ip();
        $oVisit = $oItem
            ->visits()
            ->where('expired_at', '>=', now())
            ->where('ip', $ip)
            ->first();
        if (is_null($oVisit)) {
            $oVisit = $oItem->visits()->create([
                'ip' => $ip,
                'expired_at' => now()->addMinutes(self::EXPIRED_MINUTES),
            ]);
        }
        return $oVisit;
    }
}
