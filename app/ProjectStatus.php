<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProjectStatus extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'project_status';
    public $timestamps = true;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->updated_by = Auth::user()->id;
        });

        static::updating(function($post) {
            $post->updated_by = Auth::user()->id;
        });
    }
    
    public function scopeStatus($query, $status){
        return $query->where('status', $status);
    }
}
