<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Product extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'products';
    public $timestamps = true;
    
    protected $fillable = ['name'];

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
