<?php

use Illuminate\Database\Seeder;
use MarkVilludo\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {	
        //web guard
    	Permission::insert([
			['name' => 'View', 'module' => 'Teams', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Add', 'module' => 'Teams', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Edit', 'module' => 'Teams', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Delete', 'module' => 'Teams', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'View', 'module' => 'Players', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Add', 'module' => 'Players', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Edit', 'module' => 'Players', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Delete', 'module' => 'Players', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'View', 'module' => 'Products', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Add', 'module' => 'Products', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Edit', 'module' => 'Products', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Delete', 'module' => 'Products', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'View', 'module' => 'Users', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Add', 'module' => 'Users', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Edit', 'module' => 'Users', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Delete', 'module' => 'Users', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'View', 'module' => 'Schedules', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Add', 'module' => 'Schedules', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Edit', 'module' => 'Schedules', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Delete', 'module' => 'Schedules', 'created_at' => date('Y-m-d H:i:s')]

    	]);
    }
}
