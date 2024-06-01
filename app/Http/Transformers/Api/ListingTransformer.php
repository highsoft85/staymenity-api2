<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Http\Transformers\Api\Common\ImageTransformerTrait;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\ListingTime;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Rule;
use App\Models\Type;
use App\Services\Image\ImageSize;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ListingTransformer extends TransformerAbstract
{
    use ImageTransformerTrait;

    /**
     * @param Listing $oItem
     * @return array
     */
    public function transform(Listing $oItem)
    {
        return [
            'id' => $oItem->id,
            'slug' => $oItem->slug,
            'name' => $oItem->name,
            'title' => $oItem->title,
            'rating' => $this->rating($oItem),
        ];
    }

    /**
     * @param Listing $oItem
     * @return array
     *
     * @deprecated
     */
    public function transformMention(Listing $oItem)
    {
        return [
            'id' => $oItem->id,
            'slug' => $oItem->slug,
            'name' => $oItem->name,
            'title' => $oItem->title,
            'type' => $this->type($oItem),
            'rating' => $this->rating($oItem),
            'image' => $oItem->image_square,
            'guests_size' => $oItem->guests_size,
            'price' => $this->price($oItem),
            'address' => $this->address($oItem),
            'reviews_length' => $this->reviewsLength($oItem),
        ];
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    public function transformCard(Listing $oItem)
    {
        return [
            'id' => $oItem->id,
            'slug' => $oItem->slug,
            'name' => $oItem->name,
            'title' => $oItem->title,
            'rating' => $this->rating($oItem),
            'type' => $this->type($oItem),
            'image' => $oItem->image_square,
            'images' => $this->listingImages($oItem, ImageSize::XL),
            'images_xs' => $this->listingImages($oItem, ImageSize::XS),
            'price' => $this->price($oItem),
            'guests_size' => $oItem->guests_size,
            'rent_time_min' => $this->rentTimeMin($oItem),
            'address' => $this->addressHidden($oItem),
            'location' => $this->location($oItem),
        ];
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    public function transformForChat(Listing $oItem)
    {
        return [
            'id' => $oItem->id,
            'slug' => $oItem->slug,
            'name' => $oItem->name,
            'title' => $oItem->title,
            'description' => $oItem->description,
            'type' => $this->type($oItem),
            'image' => $oItem->image_square,
            'images' => $this->listingImages($oItem, ImageSize::XL),
            'images_xs' => $this->listingImages($oItem, ImageSize::XS),
            //'price' => $this->price($oItem),
            //'guests_size' => $oItem->guests_size,
            //'rent_time_min' => $this->rentTimeMin($oItem),
            //'address' => $this->address($oItem),
            //'location' => $this->location($oItem),
        ];
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    public function transformCardForHost(Listing $oItem)
    {
        $data = $this->transformCard($oItem);
        $data = array_merge($data, [
            'address' => $this->address($oItem),
            'views' => visit()->countMonthly($oItem),
            'status' => $this->status($oItem),
            'is_published' => $oItem->published_at !== null,
        ]);
        return $data;
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    public function transformCardForReservation(Listing $oItem)
    {
        $data = $this->transformCard($oItem);
        $data = array_merge($data, [
            'address' => $this->address($oItem),
        ]);
        return $data;
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    public function transformDetail(Listing $oItem)
    {
        return [
            'id' => $oItem->id,
            'slug' => $oItem->slug,
            'name' => $oItem->name,
            'title' => $oItem->title,
            'rating' => $this->rating($oItem),
            'type' => $this->type($oItem),
            'image' => $oItem->image_square,
            'images' => $this->listingImages($oItem, ImageSize::XL),
            'images_xs' => $this->listingImages($oItem, ImageSize::XS),
            'price' => $this->price($oItem),
            'deposit' => $this->deposit($oItem),
            'cleaning_fee' => $this->cleaningFee($oItem),
            'guests_size' => $oItem->guests_size,
            'rent_time_min' => $this->rentTimeMin($oItem),
            //'distance' => '15 mi away',
            'address' => $this->addressHidden($oItem),
            'description' => $oItem->description,
            'amenities' => $this->amenities($oItem),
            'amenities_description' => $this->amenitiesDescription($oItem),
            'rules' => $this->rules($oItem),
            'rules_description' => $this->rulesDescription($oItem),
            'cancellation' => $this->cancellation($oItem),
            'location' => $this->location($oItem),
            'host' => $this->host($oItem),
            'times' => $this->times($oItem),
            'dates' => $this->dates($oItem),
            'reviews' => $this->reviews($oItem),
            'reviews_length' => $this->reviewsLength($oItem),
        ];
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    public function transformDetailForHost(Listing $oItem)
    {
        $data = $this->transformDetail($oItem);
        $data = array_merge($data, [
            'address' => $this->address($oItem),
            'amenities' => $this->amenitiesForEdit($oItem),
            'rules' => $this->rulesForEdit($oItem),
            'address_two' => $oItem->settings->address_two ?? null,
            'integrations' => $this->integrations($oItem),
        ]);
        return $data;
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function rating(Listing $oItem)
    {
        $originalValue = $oItem->ratingsToAverageByReview();
        $count = $oItem->ratingsToCountByReview();
        return (new RatingTransformer())->transform($originalValue, $count);
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function reviews(Listing $oItem)
    {
        return $oItem->reviewsActiveOrdered()->take(4)->get()->transform(function (Review $item) {
            return (new ReviewTransformer())->transform($item);
        })->toArray();
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function reviewsLength(Listing $oItem)
    {
        return $oItem->reviewsActiveOrdered()->count();
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function amenities(Listing $oItem)
    {
        return $oItem->amenitiesActive()->notOther()->get()->transform(function (Amenity $item) {
            return (new AmenityTransformer())->transform($item);
        })->toArray();
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function amenitiesForEdit(Listing $oItem)
    {
        return $oItem->amenitiesActive()->get()->transform(function (Amenity $item) use ($oItem) {
            $aItem = (new AmenityTransformer())->transform($item);
            if ($aItem['name'] === Rule::NAME_OTHER) {
                $aItem['title'] = $oItem->settings->amenities;
            }
            return $aItem;
        })->toArray();
    }

    /**
     * @param Listing $oItem
     * @return string|null
     */
    private function amenitiesDescription(Listing $oItem)
    {
        $oOther = $oItem->amenitiesActive()->other()->first();
        if (is_null($oOther)) {
            return null;
        }
        if (is_null($oItem->settings)) {
            return null;
        }
        return $oItem->settings->amenities;
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function rules(Listing $oItem)
    {
        return $oItem->rulesActive()->notOther()->get()->transform(function (Rule $item) use ($oItem) {
            $aItem = (new RuleTransformer())->transform($item);
            if ($aItem['name'] === Rule::NAME_OTHER) {
                $aItem['title'] = $oItem->settings->rules;
            }
            return $aItem;
        })->toArray();
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function rulesForEdit(Listing $oItem)
    {
        return $oItem->rulesActive()->get()->transform(function (Rule $item) use ($oItem) {
            $aItem = (new RuleTransformer())->transform($item);
            if ($aItem['name'] === Rule::NAME_OTHER) {
                $aItem['title'] = $oItem->settings->rules;
            }
            return $aItem;
        })->toArray();
    }

    /**
     * @param Listing $oItem
     * @return string|null
     */
    private function rulesDescription(Listing $oItem)
    {
        $oOther = $oItem->rulesActive()->other()->first();
        if (is_null($oOther)) {
            return null;
        }
        if (is_null($oItem->settings)) {
            return null;
        }
        return $oItem->settings->rules;
    }

    /**
     * @param Listing $oItem
     * @return string
     */
    private function cancellation(Listing $oItem)
    {
        $freeCancellationAt = now()->addHours(Reservation::FREE_CANCELLATION);
        //$freeCancellationAt->addHour();
        $formatting = $freeCancellationAt->format('h A, F d');
        return 'Free cancellation before ' . $formatting . '. After that you will be charged $' . Reservation::CANCELLATION_CHARGE . '.';
    }

    /**
     * @param Listing $oItem
     * @return array|null
     */
    private function location(Listing $oItem)
    {
        if (is_null($oItem->location)) {
            return null;
        }
        return (new LocationTransformer())->transform($oItem->location);
    }

    /**
     * @param Listing $oItem
     * @return string|null
     */
    private function address(Listing $oItem)
    {
        if (is_null($oItem->location)) {
            return null;
        }
        return (new LocationTransformer())->getAddress($oItem->location);
    }

    /**
     * @param Listing $oItem
     * @return string|null
     */
    private function addressHidden(Listing $oItem)
    {
        if (is_null($oItem->location)) {
            return null;
        }
        return (new LocationTransformer())->getAddressHidden($oItem->location);
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function host(Listing $oItem)
    {
        return (new UserTransformer())->transformHost($oItem->user);
    }

    /**
     * @param Listing $oItem
     * @return int|null
     */
    private function price(Listing $oItem)
    {
        return (int)$oItem->price;
    }

    /**
     * @param Listing $oItem
     * @return int|null
     */
    private function deposit(Listing $oItem)
    {
        return (int)$oItem->deposit;
    }

    /**
     * @param Listing $oItem
     * @return int|null
     */
    private function cleaningFee(Listing $oItem)
    {
        return (int)$oItem->cleaning_fee;
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function type(Listing $oItem)
    {
        $oType = $oItem->type;
        $oTransformer = new TypeTransformer();
        if ($oType->name === Type::NAME_OTHER) {
            return $oTransformer->transformOtherType($oType, $oItem->settings->type);
        }
        return $oTransformer->transform($oType);
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function times(Listing $oItem)
    {
        $aTimes = [
            ListingTime::TYPE_WEEKDAYS => [],
            ListingTime::TYPE_WEEKENDS => [],
        ];
        /** @var ListingTime[] $oTimes */
        $oTimes = $oItem->times()->orderBy('id', 'asc')->get();
        foreach ($oTimes as $oTime) {
            $aTimes[$oTime->type][] = [
                'from' => Carbon::parse($oTime->from)->format('h:i A'),
                'to' => Carbon::parse($oTime->to)->format('h:i A'),
            ];
        }
        return $aTimes;
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function calendar(Listing $oItem)
    {
        return $oItem->calendarDates()->pluck('date_at')->toArray();
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function dates(Listing $oItem)
    {
        return [
            'locked' => $oItem
                ->calendarDates()
                ->active()
                ->notInSearch()
                ->orderBy('date_at')
                ->pluck('date_at')->transform(function ($value) {
                    return $value->format('Y-m-d');
                })->toArray(),
        ];
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function integrations(Listing $oItem)
    {
        return [
            'hostfully' => [
                'property_uid' => $oItem->hostfully->uid ?? null,
                'active' => !is_null($oItem->hostfully) ? $oItem->hostfully->is_channel_active === 1 : false,
            ]
        ];
    }

    /**
     * @param Listing $oItem
     * @return array
     */
    private function status(Listing $oItem)
    {
        return $oItem->currentStatus;
    }

    /**
     * @param Listing $oItem
     * @return int
     */
    private function rentTimeMin(Listing $oItem)
    {
        return $oItem->rent_time_min;
    }
}
