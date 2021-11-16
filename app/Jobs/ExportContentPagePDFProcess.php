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

class ExportContentPagePDFProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $_styleId;

    private $_style;

    private $_data;

    private $_userId;

    private $_albumId;

    private $_fileName;

    private $_currentPage;

    private $_totalPage;

    private $_createTime;

    /**
     * Create a new job instance.
     *
     * @param int $styleId
     * @param array $style
     * @param array $data
     * @param int $userId
     * @param int $albumId
     * @param String $fileName
     * @param int $currentPage
     * @param int $totalPage
     * @param int $createTime
     */
    public function __construct(int $styleId, Array $style, Array $data, int $userId, int $albumId, String $fileName, int $currentPage, int $totalPage, int $createTime)
    {
        $this->_styleId = $styleId;
        $this->_style = $style;
        $this->_data = $data;
        $this->_userId = $userId;
        $this->_albumId = $albumId;
        $this->_fileName = $fileName;
        $this->_currentPage = $currentPage;
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
        $exportService->exportContentPagePDF($this->_styleId, $this->_style, $this->_data, $this->_userId, $this->_albumId, $this->_fileName, $this->_currentPage, $this->_totalPage, $this->_createTime);
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
        Log::error($exception->getMessage());
        Log::error($exception->getTraceAsString());
    }
}
