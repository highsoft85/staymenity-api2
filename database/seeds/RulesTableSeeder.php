<?php

declare(strict_types=1);

use App\Models\Rule;
use Illuminate\Database\Seeder;

/**
 * Class TermRulesTableSeeder
 */
class RulesTableSeeder extends Seeder
{
    /**
     * @return array
     */
    private function data()
    {
        return [
            [
                'title' => 'No Parties',
            ], [
                'title' => 'No Smoking',
                'icon' => asset('svg/rules/no-smoking.svg'),
            ], [
                'title' => 'No Alcohol',
                'icon' => asset('svg/rules/no-parties.svg'),
            ], [
                'title' => 'No Glass',
            ], [
                'title' => 'No Loud Music',
            ], [
                'title' => 'No Food',
            ], [
                'title' => 'No Pets',
                'icon' => asset('svg/rules/pet-friendly.svg'),
            ], [
                'title' => 'Clean-up Amenity Space',
            ], [
                'title' => 'No Shoes Inside',
            ], [
                'title' => 'Guest Limit',
            ], [
                'title' => 'Other',
                'name' => \App\Models\Rule::NAME_OTHER,
                'priority' => -1,
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data() as $data) {
            $name = isset($data['name']) ? $data['name'] : \Illuminate\Support\Str::slug($data['title']);
            $data['type'] = Rule::TYPE_LISTING;
            if (isset($data['icon'])) {
                $icon = $data['icon'];
                $aIcon = explode('svg/rules/', $icon);
                if (isset($aIcon[1])) {
                    $icon = str_replace('.svg', '', $aIcon[1]);
                }
                $data['icon'] = $icon;
            }
            $oItem = Rule::where('name', $name)->first();
            if (is_null($oItem)) {
                factory(Rule::class)->create($data);
            } else {
                $oItem->update($data);
            }
        }
        $oItems = Rule::where('name', '<>', Rule::NAME_OTHER)->get()->sortByDesc('title')->values();
        foreach ($oItems as $key => $oItem) {
            /** @var Rule $oItem */
            $oItem->update([
                'priority' => $key * 10,
            ]);
        }
    }
}
