<?php

namespace App\Console\Commands;

use App\Models\Interfaces\AdministratorInterface;
use App\Models\Interfaces\GroupInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var AdministratorInterface  */
    private $administratorEloquent;

    /** @var GroupInterface  */
    private $groupEloquent;

    /**
     * Create a new command instance.
     *
     * @param AdministratorInterface $administrator
     * @param GroupInterface $group
     * @return void
     */
    public function __construct(
        AdministratorInterface $administrator,
        GroupInterface $group
    ) {
        parent::__construct();

        $this->administratorEloquent = $administrator;
        $this->groupEloquent = $group;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (is_null($this->groupEloquent->newQuery()->find(1))) {
            $this->info('groups.id = 1 not exists. You should run `php artisan db:seed` to create it.');
            return 1;
        }

        $name = $this->ask('Please enter name.');
        $email = $this->ask('Please enter email.');
        $pw = $this->ask('Please enter password.');
        $pwCfm = $this->ask('Please enter password again for confirm.');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'pw' => $pw,
            'pwCfm' => $pwCfm,
        ], [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:App\Models\Eloquents\Administrator,email', 'max:255'],
            'pw' => ['required', 'min:8', 'max:255', 'same:pwCfm'],
        ]);

        if ($validator->fails()) {
            $this->info('Failed to create admin. (1)');
            $this->info('See error messages below:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $this->info('Create administrator...');

        $fill = [
            'group_id' => 1,
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($pw),
        ];

        $administrator = $this->administratorEloquent->newInstance($fill);
        $result = $administrator->save();

        if (!$result) {
            $this->error('Failed to create admin. (2)');
            return 1;
        }

        $this->info('Success to create admin.');
    }

}
