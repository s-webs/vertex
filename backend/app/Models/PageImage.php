<?php

namespace App\Models;

use Database\Factories\PageImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['page_key', 'slot_key', 'path', 'alt'])]
class PageImage extends Model
{
    /** @use HasFactory<PageImageFactory> */
    use HasFactory;
}
