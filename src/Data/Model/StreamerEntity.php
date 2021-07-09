<?php

declare(strict_types=1);

namespace App\Data\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StreamerEntity extends Model
{
    use SoftDeletes;

    protected $table = 'streamers';
    protected $fillable = ['code', 'name'];
}
