<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class RealtorListingAcceptOfferController extends Controller
{
    public function __invoke(Offer $offer)
    {
        $listing = $offer->listing;
        $this->authorize('update', $listing);

        $offer->update(['accepted_at' => now()]);

        $listing->sold_at = now();
        $listing->save();

        $offer->listing->offers()->except($offer)
            ->update(['rejected_at'  => now()]);

        return redirect()->back()
            ->with(
                'success',
                "offer #{$offer->id} accepted, other offers rejected"
            );
    }
}
