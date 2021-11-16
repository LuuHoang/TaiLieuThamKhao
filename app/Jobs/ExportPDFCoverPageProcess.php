<?php

namespace App\Jobs;

use App\Services\ExportService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExportPDFCoverPageProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $_format;

    private $_data;

    private $_userId;

    private $_albumId;

    private $_fileName;

    private $_createTime;

    private $_totalPage;

    /**
     * Create a new job instance.
     *
     * @param array $format
     * @param array $data
     * @param int $userId
     * @param int $albumId
     * @param String $fileName
     * @param int $totalPage
     * @param int $createTime
     */
    public function __construct(Array $format, Array $data, int $userId, int $albumId, String $fileName, int $totalPage, int $createTime)
    {
        $this->_format = $format;
        $this->_data = $data;
        $this->_userId = $userId;
        $this->_albumId = $albumId;
        $this->_fileName = $fileName;
        $this->_totalPage = $totalPage;
        $this->_createTime = $createTime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $exportService = app(ExportService::class);
        $exportService->exportPDFCoverPage($this->_format, $this->_data, $this->_userId, $this->_albumId, $this->_fileName, $this->_totalPage, $this->_createTime);
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {
        // Send user notification of failure, etc...
        $exportService = app(ExportService::class);
        $exportService->removeAlbumPDFFailed($this->_albumId, $this->_format['id'], $this->_fileName);
        Log::error($exception->getMessage());
        Log::error($exception->getTraceAsString());
    }
}
