<?php

namespace App\Console\Commands;

use App\Helpers;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseBackup extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup of database dump.';

    protected $process;
    protected $backupFileName;
    protected $backupFilePath;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();

        $this->backupFileName = 'db_backup_'.date('Ymd').'.sql';
        $this->backupFilePath = storage_path('backups/'.$this->backupFileName);

        $this->process = Process::fromShellCommandline(sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username', env('DB_USERNAME', '')),
            config('database.connections.mysql.password', env('DB_PASSWORD', '')),
            config('database.connections.mysql.database', env('DB_DATABASE', '')),
            $this->backupFilePath
        ));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->output->title('Backup database dump to dropbox');

        $dropbox = Storage::disk('dropbox');
        $localdisk = Storage::disk('backups');

        try {
            $this->process->mustRun();

            $this->info($this->process->getOutput());

            $fileSize = $localdisk->size($this->backupFileName);

            $this->output->success('The backup has been proceed successfully.');
            $this->info(
                'Filename: ' . $this->backupFileName
            );
            $this->info(
                'Filesize: ' . Helpers::formatFilesize($fileSize)
            );

            $this->output->text('Uploading backup file to dropbox.');

            $remoteFilePath = 'backups/'.$this->backupFileName;

            $fileContent = $localdisk->get($this->backupFileName);

            $startAt = microtime(true);
            $success = $dropbox->put($remoteFilePath, $fileContent);
            $endAt = microtime(true);
            $duration = $endAt - $startAt;
            if($success) {
                $this->info(
                    'Upload duration: ' .
                    number_format($duration, 3) . ' seconds'
                );
                $this->info(
                    'Upload speed: ' .
                    Helpers::formatFilesize(($fileSize/$duration)) . '/s'
                );
                $this->output->success('The backup file has been uploaded to dropbox successfully.');
                if($localdisk->delete($this->backupFileName)) {
                    $this->output->success('The backup file has been deleted locally.');
                }

                return true;
            }

        } catch (ProcessFailedException $e) {
            $this->output->error('The backup process has failed.');
            $this->info($this->process->getErrorOutput());
        } catch(\Exception $e) {
            $this->output->error('The backup file processing has failed.');
            $this->info($e->getMessage());
        }

        return false;
    }

}
