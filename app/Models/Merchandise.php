<?php

namespace App\Models;

use App\Enums\MerchandiseStatusEnum;
use App\Enums\VisibilityEnums;
use App\Observers\MerchandiseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


#[ObservedBy(MerchandiseObserver::class)]
class Merchandise extends Model
{
    use HasFactory;

    protected $table = 'events';


    protected $fillable = [   
        'name',
        'image',
        'user_id',

 'deskripsi' ,
 'status',
 'files' ,
 'quantity',
 'price',
 'visibility'
     ];
 
 
    
     protected $casts = 
     [  
         'name' => 'string',
         'image' => 'string',
   'deskripsi' => 'string',
   'quantity' => 'integer',
   'price' => 'decimal:2',
   'status' => MerchandiseStatusEnum::class,
   'visibility' => VisibilityEnums::class,
   'files' => 'array',

     ]; 
 

     public function user(): BelongsTo
     {
         return $this->belongsTo(User::class);
     }
 

}
