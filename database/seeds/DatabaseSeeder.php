<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\Checklist::class, 100)->create()->each(function ($checklist) {
        //     $checklist->items()->saveMany(factory(App\Item::class, 10)->make());
        // });

        // factory(App\Template::class, 5)->create()->each(function ($template) {
        //     $template->checklist()->save(factory(App\Checklist::class)->make());
        // });   

        $template = factory(App\Template::class, 100)->create();
        $template->map(function ($tmp) {
            $tmp = $tmp->checklist()->save(factory(App\Checklist::class)->make());
            $tmp->items()->saveMany(factory(App\Item::class, 2)->make());
        });
        // $template->sections()->saveMany(factory(App\Checklist::class)->make());

        // foreach ($template->sections as $section){
        //     $section->actions()->saveMany(factory(App\Item::class, 10)->make());
        // }
    }
}
