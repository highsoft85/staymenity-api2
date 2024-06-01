<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Faq;

class FaqsTableSeeder extends Seeder
{
    use \App\Database\Seeds\CommonDatabaseSeederTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->before(Faq::class);

        factory(Faq::class)->create([
            'title' => 'What amenity spaces are listed on Staymenity?',
            'description' => 'Use the Amenities listed on the survey results in that order.',
            'priority' => 100,
        ]);
        factory(Faq::class)->create([
            'title' => 'How can I ensure that the prospective guest is reliable?',
            'description' => 'Staymenity provides hosts with background checks and guest risk ratings. Past reviews of guests are available to prospective hosts. Hosts are then given a decision whether or not to move forward with the booking. Hosts can bypass this process if they so choose and offer “instant-booking”. Hosts can require that guests who book with “instant-booking” have background checks.',
            'priority' => 80,
        ]);
        factory(Faq::class)->create([
            'title' => 'What is the duration of time that my property can be rented?',
            'description' => 'Hosts can rent their amenity space by the hour, half day and/or full day. Half and full day hours are determined by the hosts. Host’s can set a minimum hour requirement if desired.',
            'priority' => 60,
        ]);
        factory(Faq::class)->create([
            'title' => 'What is the service fee charged?',
            'description' => 'There is no fee to sign up and build a listing profile. Staymentiy charges a fee % to the host once the transaction between the host and guest is completed (following check-out).',
            'priority' => 40,
        ]);
        factory(Faq::class)->create([
            'title' => 'How do I get started?',
            'description' => 'Hosts will have the ability to create an account in 5 minutes or less at no cost. Staymenity will ensure that listings are desirable for prospective guests by offering hosts suggestions on features offered as well as guidance on descriptions, photos, host bio, etc. Staymenity will assist in advertising listings.',
            'priority' => 30,
        ]);

        $this->after();
    }
}
