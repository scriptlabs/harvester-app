<?php

namespace App\Console\Commands;

use App\Helpers;
use App\Models\Captures;
use Illuminate\Console\Command;

class CapturesBackup extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'captures:backup {--limit=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup of captured images.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->output->title('Backup captures to dropbox');

        $limit = 5;
        if($this->hasOption('limit')) {
            $limit = $this->option('limit');
        }
        $this->output->text('Limit set to: '.$limit);

        /** @var Captures[] $captures */
        $captures = Captures::whereNull('uploaded_at')
                            ->limit($limit ? $limit : 5)
                            ->orderBy('id', 'DESC')
                            ->get();

        $failed = 0;
        $success = 0;
        $total = $captures ? count($captures) : 0;

        $this->output->text('Captures found: '.$total);
        $this->output->newLine(1);

        $totalFileSize = 0;
        $totalStartAt = microtime(true);

        if(!$total) {
            $this->info('No captures found!');
            return true;
        }

        $this->comment('Upload starting...');
        $this->output->newLine(1);

        $step = 1;
        $this->output->progressStart($total);
        $this->output->newLine(2);
        /** @var Captures $capture */
        foreach ($captures as $capture) {
            $this->output->section('Uploading file: '. $step.'/'.$total);

            $fileSize = $capture->file_size;
            $totalFileSize += $fileSize;

            $this->output->block([
                'File name: ' .
                $capture->file_name,

                'File size: ' .
                Helpers::formatFilesize(($fileSize))
            ]);

            $startAt = microtime(true);
            $result = $capture->uploadToDropbox();
            $endAt = microtime(true);

            $duration = $endAt - $startAt;

            $this->info('Done');

            $this->output->block([
                'Upload duration: ' .
                number_format($duration, 3) . ' seconds',

                'Upload speed: ' .
                Helpers::formatFilesize(($fileSize/$duration)) . '/s'
            ]);

            $this->output->progressAdvance(1);
            $this->output->newLine(2);

            if($result) {
                $success++;
                $this->output->success('Capture '.$capture->file_name.' uploaded.');

                $deleted = $capture->removeLocalFile();
                $this->info('Local file'.($deleted ? '' : ' not').' deleted!');
            }
            else {
                $failed++;
                $this->output->error('Capture '.$capture->file_name.' not uploaded.');
            }

            $step++;
        }

        $totalEndAt = microtime(true);
        $totalDuration = $totalEndAt - $totalStartAt;

        $this->info('Upload finished.');

        $this->output->newLine(1);
        $this->output->text('Captures failed: '.$failed);
        $this->output->text('Captures successful: '.$success);
        $this->output->newLine(1);

        $this->output->text(
            'Total files: ' .
            $total
        );
        $this->output->text(
            'Total upload size: ' .
            Helpers::formatFilesize(($totalFileSize))
        );
        $this->output->text(
            'Total upload duration: ' .
            number_format($totalDuration, 3) . ' seconds'
        );
        $this->output->text(
            'Total upload speed: ' .
            Helpers::formatFilesize(($totalFileSize/$totalDuration)) . '/s'
        );
        // cleanup (remove empty directories)
        $rootPath = storage_path('app/webcam/');
        Captures::removeEmptySubfolders($rootPath);

        $this->output->newLine(1);

        return true;
    }

}
