<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuDocumentClassification extends Model
{
    use IsLookup;

    protected $table = 'lu_document_classifications';

    protected $fillable = [
        'code', 'label_en', 'label_fr', 'label_rw', 'retention_years',
        'is_active', 'sort_order',
    ];
}
