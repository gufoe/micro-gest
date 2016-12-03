<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Faker\Faker;

class GenContacts extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'gen-contacts';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate ten thousands random contacts';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // use the factory to create a Faker\Generator instance
        $faker = \Faker\Factory::create();

        \DB::beginTransaction();
        for ($i = 0; $i < 10000; $i++) {
            if ($i && $i % 10 == 0) {
                $this->comment("Generated {$i} contacts");
            }
            \App\Contact::create([
                'name'  => $faker->name,
                'email' => str_random(3).'-'.$faker->email,
            ]);
        }

        \DB::commit();

        $this->comment('Done');
    }
}
