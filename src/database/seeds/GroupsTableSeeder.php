<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * @param \App\Models\Interfaces\GroupInterface $groupEloquent
     */
    public function run(\App\Models\Interfaces\GroupInterface $groupEloquent)
    {
        if (is_null($groupEloquent->newQuery()->find(1))) {
            $group = $groupEloquent->newInstance([
                'name' => 'administrator',
            ]);
            $group->save();
        }
    }
}
