<?php

namespace Gluon\Backend\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gluon:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new client and admin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::transaction(function() {
            $this->info("Setting up system. Remember to run `php artisan migrate´ before");
            $cname = $this->ask('Client name?');
            $name = $this->ask('Admin user name?');
            $email = $this->ask('Admin user email?');
            $pwd = $this->secret('Admin user password?');

            $client = DB::table('client')->insertGetId([
                'name' => $cname
            ]);
            $role = DB::table('role')->insertGetId([
                'name' => 'Admin',
                'client_id' => $client,
            ]);
            DB::table('right')->insertGetId([
                'role_id' => $role,
                'client_id' => $client,
                "tables" => "*",
                "columns" => "*",
                "where" => "*",
                "actions" => "CRUD",
            ]);
            DB::table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($pwd),
                'client_id' => $client,
                'role_id' => $role,
            ]);
        });
    }
}
