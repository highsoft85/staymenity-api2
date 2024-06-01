<?php

declare(strict_types=1);

namespace App\Services\Modelable;

use App\Models\Review;

/**
 * Trait Locationable
 * @package App\Services\Modelable
 *
 * @property Review[] $reviews
 * @property Review[] $reviewsActiveOrdered
 */
trait Reviewable
{
    /**
     * Отзывы оставленные на этого пользователя
     *
     * @return mixed|Review
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Отзывы оставленные на этого пользователя
     *
     * @return mixed
     */
    public function reviewsActiveOrdered()
    {
        return $this->reviews()->active()->ordered();
    }
}
