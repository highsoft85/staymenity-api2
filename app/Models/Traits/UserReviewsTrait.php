<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Listing;
use App\Models\Review;

/**
 * Trait UserReviewsTrait
 * @package App\Models\Traits
 *
 * @property Review[] $reviews
 * @property Review[] $reviewsActiveOrdered
 */
trait UserReviewsTrait
{
    /**
     * Отзывы оставленные эти пользователем
     *
     * @return mixed
     */
    public function reviewers()
    {
        return $this->hasMany(Review::class, 'user_id');
    }
}
