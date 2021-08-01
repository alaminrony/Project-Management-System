<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductPhoto extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'product_photos';
    public $timestamps = true;
    
    protected $fillable = ['product_id', 'filename'];
    
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

   
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
