<?php

namespace App\Jobs;

class RunRestApiTests extends BaseJob
{

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private int $userId,
        private int $endPointId
    ){
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userId = $this->userId;
        $endPointId = $this->endPointId;
    }
}
