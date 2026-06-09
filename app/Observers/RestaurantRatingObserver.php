<?php

namespace App\Observers;

use App\Models\RestaurantRating;

class RestaurantRatingObserver
{
    // Ek helper function taaki code DRY (Repeat na ho) rahe
    private function updateRestaurantRating(RestaurantRating $rating)
    {
        $restaurant = $rating->restaurant;

        if ($restaurant) {
            // Naya average aur total count nikalo
            $totalReviews = $restaurant->ratings()->count();
            $avgRating = $restaurant->ratings()->avg('rating') ?? 0.0;

            // Restaurant table ko update kar do
            $restaurant->update([
                'total_reviews' => $totalReviews,
                'avg_rating' => round($avgRating, 1) // 4.25 ko 4.3 kar dega
            ]);
            
            // 💡 MAGIC: Jaise hi update() chalega, Laravel Scout automatically 
            // is naye data ko Meilisearch engine mein sync kar dega!
        }
    }

    public function created(RestaurantRating $restaurantRating)
    {
        $this->updateRestaurantRating($restaurantRating);
    }

    public function updated(RestaurantRating $restaurantRating)
    {
        $this->updateRestaurantRating($restaurantRating);
    }

    public function deleted(RestaurantRating $restaurantRating)
    {
        $this->updateRestaurantRating($restaurantRating);
    }
}