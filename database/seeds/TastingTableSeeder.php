<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class TastingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Models\Institution::get() as $institution){
            Auth::login($institution->owner);
            factory(\App\Models\Tasting::class, 20)
                ->create([
                    'institution_id' => $institution->id,
                    'location_id' => factory(\App\Models\Location::class)->create([])->id
                ])
                ->map(function($tastingParent){

                    $products = \App\Models\Product::where('institution_id', $tastingParent->institution_id)
                        ->inRandomOrder()->limit(rand(1,5))->get();
                    $tastingParent->products()->sync($products);
                    $tasting = $tastingParent->toArray();
                    array_pull($tasting, 'id');
                    array_pull($tasting, 'parent');

                    $location = $tastingParent->location->toArray();
                    array_pull($location, 'id');

                    $date = \Carbon\Carbon::parse($tastingParent->dateStart);

                    $i = 0;
                    do {
                        $tastingChild = new \App\Models\Tasting;
                        $tastingChild->fill($tasting);
                        $tastingChild->is_parent =false;
                        $tastingChild->parent_id = $tastingParent->id;
                        $dateChild = clone $date;
                        switch ($tastingParent->type){
                            case 'MONTHLY':
                                $tastingChild->date = $dateChild->addMonths($i)->format('Y-m-d');
                                break;
                            case 'WEEKLY':
                                $tastingChild->date = $dateChild->addWeeks($i)->format('Y-m-d');
                                break;
                            case 'DAILY':
                                $tastingChild->date = $dateChild->addDays($i)->format('Y-m-d');
                                break;
                            case 'SINGLE':
                                $tastingChild->date = $dateChild->format('Y-m-d');
                                break;
                        }

                        if($tastingChild->date > $tastingParent->dateEnd){
                            break;
                        }else{
                            $i++;
                        }

                        $locationChild = new \App\Models\Location();
                        $locationChild->fill($location);
                        $locationChild->save();
                        $tastingChild->location_id =  $locationChild->id;

                        $tastingChild->save();

                    } while ($date->format('Y-m-d') < $tastingParent->dateEnd);
                });
        }

    }
}
