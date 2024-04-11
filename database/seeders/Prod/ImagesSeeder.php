<?php

namespace Database\Seeders\Prod;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImagesSeeder extends Seeder
{
    /**
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $connection = ssh2_connect(config('ssh.url'), config('ssh.port'));

        if (!ssh2_auth_password($connection, config('ssh.username'), config('ssh.password'))) {
            throw new Exception('Unable to connect ' . config('ssh.url') . 'with SSH!');
        }

        // Create our SFTP resource
        if (!$sftp = ssh2_sftp($connection)) {
            throw new Exception('Unable to create SFTP connection.');
        }

        $this->copyFileViaSSH($connection, $sftp, 'items');
        $this->copyFileViaSSH($connection, $sftp, 'categories');
    }

    /**
     * @param $connection
     * @param $sftp
     * @param string $dir
     * @return void
     */
    private function copyFileViaSSH($connection, $sftp, string $dir): void
    {
        $this->command->info("dir = " . $dir);
        $localFiles = Storage::disk('uploads')->files($dir);
        $localFileNames = array_map(fn($file) => File::basename($file), $localFiles);
        $remoteDir = config('ssh.remote_dir') . '/public/uploads' . '/' . $dir;
        $localDir = Storage::disk('uploads')->path($dir);
        $remoteFiles = scandir('ssh2.sftp://' . $sftp . $remoteDir);
        $remoteFiles = array_filter($remoteFiles, fn($value) => $value !== "." && $value !== "..");
        $difference = array_diff($remoteFiles, $localFileNames);

        if (empty($difference)) {
            $this->command->info("Для " . $dir . ' новых файлов нет!');
        }

        foreach ($difference as $file) {
            try {
                ssh2_scp_recv($connection, "$remoteDir/$file", "$localDir/$file");
                $this->command->info("$remoteDir/$file copied to $localDir/$file");
            } catch (Exception $e) {
                $this->command->error("Failed to copy $remoteDir/$file: {$e->getMessage()}");
            }
        }
    }
}
