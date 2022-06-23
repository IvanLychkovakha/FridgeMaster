<?php

use App\Models\FreezingRoom;

if (!function_exists('get_total_available_volume')) {
 

    function get_total_available_volume($freezing_room_id)
    {
        $room = FreezingRoom::find($freezing_room_id);
        $blocks = $room->blocks()->where('empty', true)->get() ?? [];
        $totalVolume = 0;

        foreach($blocks as $item)
        {
           $totalVolume += $item->volume;
        }
        
        return $totalVolume;
    }
}