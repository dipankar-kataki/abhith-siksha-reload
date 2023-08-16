<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SelectedAddon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table ='selected_addons';
    protected $guarded = [];


    public function selectedAddon(){
        return $this->belongsTo(Addon::class, 'addon_id', 'id');
    }
}
