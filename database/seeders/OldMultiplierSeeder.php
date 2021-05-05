<?php

namespace Database\Seeders;

use App\Models\Multiplier;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OldMultiplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $histories = [
            [ 18, "Entrier", "2021-04-01 12:06:45" ],
            [ 20, "Entries", "2021-04-02 10:12:01" ],
            [ 5, "Entries", "2021-04-11 07:02:01" ],
            [ 10, "Entries", "2021-04-14 07:00:10" ],
            [ 5, "Entries", "2021-04-19 06:59:03" ],
            [ 5, "Entries", "2021-04-21 07:00:00" ],
            [ 10, "Entries", "2021-04-29 07:01:08" ],
            [ 10, "Entries", "2021-04-29 07:01:12" ],
            [ 5, "Entries", "2021-05-03 06:59:59" ],
            [ 5, "Entries", "2021-05-03 07:00:04" ],
        ];

        if ( count( $histories ) > 0 ) {
            foreach ( $histories as $history ) {
                $date = Carbon::parse( $history[2] );
                $oldMultiplier = new Multiplier;
                $oldMultiplier->value = $history[0];
                $oldMultiplier->label = $history[1];
                $oldMultiplier->giveaway_start_date = $date;
                $oldMultiplier->giveaway_end_date = $date->addDays( 3 );
                $oldMultiplier->save();

                $id = $oldMultiplier->id;
                $multiplier = Multiplier::find( $id );
                $multiplier->created_at = Carbon::parse( $history[2] );
                $multiplier->created_at = Carbon::parse( $history[2] );
                $multiplier->save();
            }
        }
    }
}
