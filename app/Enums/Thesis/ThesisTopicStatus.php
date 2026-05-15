<?php

namespace App\Enums\Thesis;

use App\Enums\Concerns\HasValues;

enum ThesisTopicStatus: string
{
    use HasValues;

    case Draft = 'Draft';
    case Submitted = 'Submitted';
    case UnderReview = 'Under Review';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Revision = 'Revision';
}
