<?php

declare(strict_types=1);

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\Hostfully\ParametersService;

class TypesTableSeeder extends Seeder
{
    /**
     * @var string[][]
     */
    private $data = [
//        [
//            'title' => 'Backyard',
//        ], [
//            'title' => 'Swimming pool',
//        ], [
//            'title' => 'Basketball playground',
//        ], [
//            'title' => 'Guest house',
//        ], [
//            'title' => 'Garage',
//        ], [
//            'title' => 'Unconventional workspace',
//        ], [
//            'name' => \App\Models\Type::NAME_OTHER,
//            'title' => 'Other',
//        ],

        [
            'title' => 'Waterview',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Private Lake',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Private Beach',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Private Island',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Private Gym',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Pool',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Hot Tub',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Beautiful Backyard',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Basketball Court',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Tennis Court',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Volleyball',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Fire Pit',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Barbecue',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Rooftop',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Outdoor Bar',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Chef\'s Kitchen',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Mansions',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Private Movie Theatre',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Private Bowling Alley',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Farm',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Garden',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Home Office',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Playground',
            'name_hostfully' => ParametersService::TYPE_HOUSE,
        ], [
            'title' => 'Guest Houses',
            'name_hostfully' => ParametersService::TYPE_GUESTHOUSE,
        ], [
            'title' => 'Unconventional Workspaces',
            'name_hostfully' => ParametersService::TYPE_OTHER,
        ], [
            'title' => 'Other',
            'name' => Type::NAME_OTHER,
            'name_hostfully' => ParametersService::TYPE_OTHER,
            'priority' => -1,
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $data) {
            $name = isset($data['name']) ? $data['name'] : \Illuminate\Support\Str::slug($data['title']);
            /** @var Type|null $oType */
            $oType = Type::where('name', $name)->first();
            if (is_null($oType)) {
                factory(Type::class)->create($data);
            } else {
                if (is_null($oType->name_hostfully) && isset($data['name_hostfully'])) {
                    $oType->update([
                        'name_hostfully' => $data['name_hostfully'],
                    ]);
                }
            }
        }
        $oItems = Type::where('name', '<>', Type::NAME_OTHER)->get()->sortByDesc('title')->values();
        foreach ($oItems as $key => $oItem) {
            /** @var Type $oItem */
            $oItem->update([
                'priority' => $key * 10,
            ]);
        }
    }
}
