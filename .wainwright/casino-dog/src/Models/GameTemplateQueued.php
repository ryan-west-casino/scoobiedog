<?php

namespace Wainwright\CasinoDog\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;
use Wainwright\CasinoDog\Jobs\GameslistImporterProcessGame;
use Wainwright\CasinoDog\CasinoDog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class GameTemplateQueued extends Eloquent  {
    protected $table = 'wainwright_gametemplate_queued';
    protected $timestamp = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'gid',
        'slug',
        'completed',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}