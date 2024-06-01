<?php

declare(strict_types=1);

namespace App\Services\Modelable;

use App\Models\Listing;
use App\Models\Review;

trait Ratingable
{
    /**
     * @return float
     */
    public function ratingsToAverageByReview()
    {
        $rating = $this->getRatingsToArrayByReviews();
        $value = collect($rating)->avg() ?? 0;
        return (float)$value;
    }

    /**
     * @return int
     */
    public function ratingsToCountByReview()
    {
        $rating = $this->getRatingsToArrayByReviews();
        return count($rating);
    }

    /**
     * @return array
     */
    private function getRatingsToArrayByReviews()
    {
        /** @var Review[] $reviews */
        $reviews = $this->reviewsActiveOrdered;
        $ratings = [];
        foreach ($reviews as $review) {
            if (!is_null($review->rating)) {
                $ratings[] = $review->rating;
            }
        }
        return $ratings;
    }
}
