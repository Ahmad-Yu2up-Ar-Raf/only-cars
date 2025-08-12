<?php

namespace App\Models;

use App\Enums\StatusEventEnum;
use App\Enums\VisibilityEnums;
use App\Observers\EventObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
#[ObservedBy(EventObserver::class)]
class Events extends Model
{
   
    use HasFactory;

    protected $table = 'events';


    protected $fillable = [   
        'title',
        'cover_image',
        'user_id',
        'location',
 'deskripsi' ,
 'status',
 'start_date',
 'end_date',
 'capacity',
 'files' ,
 'visibility' ,
     ];
 
 
    
     protected $casts = 
     [  
         'title' => 'string',
         'cover_image' => 'string',
   'deskripsi' => 'string',
   'location' => 'string',
   'capacity' => 'integer',
   'start_date' => 'datetime',
   'end_date' => 'datetime',
   'files' => 'array',
   'visibility' => VisibilityEnums::class,
   'status' => StatusEventEnum::class,
  
     ]; 
 

     public function user(): BelongsTo
     {
         return $this->belongsTo(User::class);
     }
 


    //  public function getFormattedPriceAttribute()
    //  {
    //      return 'Rp ' . number_format($this->harga, 0, ',', '.');
    //  }
 
    //  /**
    //   * Method untuk format DP minimum
    //   */
    //  public function getFormattedDpAttribute()
    //  {
    //      if (!$this->dp_minimum) {
    //          return null;
    //      }
         
    //      return 'Rp ' . number_format($this->dp_minimum, 0, ',', '.');
    //  }
}
