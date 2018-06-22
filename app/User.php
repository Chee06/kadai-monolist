<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Controllers\Controller;
use App\Item;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
//item------------------------------------------------------------------------------------------------------
   public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('type')->withTimestamps();
    }
    
//want_items-------------------------------------------------------------------------------------------------
    public function want_items()
    {
        return $this->items()->where('type', 'want');
    }
    
//want------------------------------------------------------------------------------------------------------
    public function want($itemId)
    {
        // Is the user already "want"?
        $exist = $this->is_wanting($itemId);

        if ($exist) {
            // do nothing
            return false;
        } else {
            // do "want"
            $this->items()->attach($itemId, ['type' => 'want']);
            return true;
        }
    }
    
//dont_want-------------------------------------------------------------------------------------------------
    public function dont_want($itemId)
    {
        // Is the user already "want"?
        $exist = $this->is_wanting($itemId);

        if ($exist) {
            // remove "want"
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'want'", [\Auth::id(), $itemId]);
        } else {
            // do nothing
            return false;
        }
    }
    
//is_wanting------------------------------------------------------------------------------------------------
    public function is_wanting($itemIdOrCode)
    {
        if (is_numeric($itemIdOrCode)) {
            $item_id_exists = $this->want_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
        } else {
            $item_code_exists = $this->want_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
        }
    }
    
//========================================================================================================    
//have_items----------------------------------------------------------------------------------------------

     public function have_items()
    {
        return $this->items()->where('type', 'have');
    }
//have-----------------------------------------------------------------------------------------------------
    public function have($itemId)
    {
        // Is the user already "want"?
        $exist = $this->is_having($itemId);

        if ($exist) {
            // do nothing
            return false;
        } else {
            // do "want"
            $this->items()->attach($itemId, ['type' => 'have']);
            return true;
        }
    }
//dont_have-----------------------------------------------------------------------------------------------
     public function dont_have($itemId)
    {
        // Is the user already "want"?
        $exist = $this->is_having($itemId);

        if ($exist) {
            // remove "want"
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'have'", [\Auth::id(), $itemId]);
        } else {
            // do nothing
            return false;
        }
    }
//is_having---------------------------------------------------------------------------------------------
     public function is_having($itemIdOrCode)
    {
        if (is_numeric($itemIdOrCode)) {
            $item_id_exists = $this->have_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
        } else {
            $item_code_exists = $this->have_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
        }
    }
}