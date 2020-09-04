<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Installers\Installer;
use App\Installers\Traits\BlockMessage;
use App\Installers\Traits\SectionMessage;

class InstallCommand extends Command
{
    use BlockMessage, SectionMessage;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vertilogic:install {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install CMS Vertilogic';

    /**
     * @var Installer
     */
    private $installer;

    /**
     * Create a new command instance.
     *
     * @param Installer $installer
     *
     * @internal param Filesystem $finder
     * @internal param Application $app
     * @internal param Composer $composer
     */
    public function __construct(Installer $installer)
    {
        parent::__construct();
        $this->getLaravel()['env'] = 'local';
        $this->installer = $installer;
    }

    /**
     * Execute the actions.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert('Vertilogic Installation');

        $success = $this->installer->stack([
            \App\Installers\Scripts\ProtectInstaller::class,
            \App\Installers\Scripts\ConfigureDatabase::class,
            \App\Installers\Scripts\PackageMigrators::class,
            \App\Installers\Scripts\PackageSeeders::class,
            \App\Installers\Scripts\SetSuperuserUser::class,
            \App\Installers\Scripts\SetAppKey::class,
        ])->install($this);

        if ($success) {
            $this->line('Vertilogic CMS is ready.');
            $this->info('You can now login with your email and password at [' . url('/backend') . ']');
        }
    }
}
