<?php

namespace App\Console\Commands;

use App\Services\DoseConfirmationService;
use Illuminate\Console\Command;

class ConfirmDose extends Command
{
    protected $signature = 'doses:confirm {id}';

    protected $description = 'Mô phỏng xác nhận uống thuốc';

    public function handle(DoseConfirmationService $service): int
    {
        $id = filter_var(
            $this->argument('id'),
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($id === false) {
            $this->error('ID ca uống không hợp lệ.');

            return self::FAILURE;
        }

        if (!$service->confirm($id)) {
            $this->warn(
                'Không thể xác nhận. Ca có thể đã xử lý, '
                . 'chưa đến giờ hoặc đã quá hạn.'
            );

            return self::FAILURE;
        }

        $this->info(
            "Đã xác nhận ca #{$id} và lưu thông báo cho NCS."
        );

        return self::SUCCESS;
    }
}
