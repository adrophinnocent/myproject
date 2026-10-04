<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTemplateExclusion extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_template_id',
        'item',
        'sort_order',
    ];

    public function template()
    {
        return $this->belongsTo(ProposalTemplate::class, 'proposal_template_id');
    }
}
