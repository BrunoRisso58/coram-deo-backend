<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "title",
        "description",
        "days",
        "reminder_days",
        "reminder_time",
    ];

    /**
     * Return if the goal should be completed on the given date
     * 
     * @param string $date
     * @return void
     */
    public function shouldBeCompletedOn(string $date): bool
    {
        $date = Carbon::parse($date);

        $days = explode(',', $this->days);

        foreach ($days as $day) {
            info(strtolower($day));
            info(strtolower($date->shortDayName));
            if (strtolower($day) === strtolower($date->shortDayName)) {
                return true;
            }
        }

        return false;
    }
}
