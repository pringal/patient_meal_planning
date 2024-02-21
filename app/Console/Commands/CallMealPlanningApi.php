<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CallMealPlanningApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:call-meal-planning-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call Meal Planning API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiEndpoint = route('api.meal-planning');

        $startDate = '2022-01-01';
        $endDate = '2023-02-31';

        //$apiEndpoint = http://127.0.0.1:8000/api/meal-planning
        $response = Http::get($apiEndpoint, [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
        $this->info('API Response:');
        $this->info($response->body());
        $this->info('Meal planning data fetched successfully.');
    }
}
