<?php

namespace App\Enums\Thesis;

use App\Enums\Concerns\HasValues;

enum ResearchReviewStatus: string
{
    use HasValues;

    case PendingReview = 'Pending Review';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
}
