<?php

namespace App\Models;

use App\Enums\VisibilityEnums;
use App\Observers\GalleryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



#[ObservedBy(GalleryObserver::class)]
class Gallery extends Model
{
    use HasFactory;

    protected $table = 'galleries';


    protected $fillable = [   
        'title',
        'files',
        'user_id',
        'visibility' ,
     ];
 
 
    
     protected $casts = 
     [  
         'title' => 'string',
         'files' => 'array',

         'visibility' => VisibilityEnums::class,
  
     ]; 
 

     public function user(): BelongsTo
     {
         return $this->belongsTo(User::class);
     }
}
