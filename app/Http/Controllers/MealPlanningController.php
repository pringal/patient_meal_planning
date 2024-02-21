<?php

namespace App\Http\Controllers;

use App\Models\PatientMealPlanning;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MealPlanningController extends Controller
{
    public function getRecordsByMonthAndYear(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $results = PatientMealPlanning::whereBetween('planned_date', [$startDate, $endDate])
            ->orderBy('planned_date','ASC')->get();
        $groupedResults = [];

        //create an array that has month wise key bases on records (planned_date)
        foreach ($results as $result) {
            $date = Carbon::parse($result->planned_date);
            $yearMonthKey = $date->format('M Y');


            if (!isset($groupedResults[$yearMonthKey])) {
                $groupedResults[$yearMonthKey] = [
                    'month' => $date->format('M Y'),
                    'count' => 0,
                    'total_calories' => [],
                    'total_fats' => [],
                    'total_carbs' => [],
                    'total_proteins' => [],
                    'planned_date_all' => [],
                    'days_in_month'=>$date->daysInMonth,
                ];
            }

            //Adding all the records in particular array and added count of existing days which has value in it
            $groupedResults[$yearMonthKey]['total_calories'][] = $result->total_calories;
            $groupedResults[$yearMonthKey]['total_fats'][] = $result->total_fats;
            $groupedResults[$yearMonthKey]['total_carbs'][] = $result->total_carbs;
            $groupedResults[$yearMonthKey]['total_proteins'][] = $result->total_proteins;
            if (!in_array($result->planned_date, $groupedResults[$yearMonthKey]['planned_date_all'])) {
                $groupedResults[$yearMonthKey]['count']++;
                $groupedResults[$yearMonthKey]['planned_date_all'][] = $result->planned_date;
            }
        }

        //Adding an average of totals and planned percentage calculation
        foreach ($groupedResults as &$processedGroupedResults){
            $total_calories_average = collect($processedGroupedResults['total_calories'])->average();
            $total_fats_average = collect($processedGroupedResults['total_fats'])->average();
            $total_carbs_average = collect($processedGroupedResults['total_carbs'])->average();
            $total_proteins_average = collect($processedGroupedResults['total_proteins'])->average();

            $processedGroupedResults['planned_percentage'] =
                ($processedGroupedResults['count'] / $processedGroupedResults['days_in_month']) * 100;
            $processedGroupedResults['avg_total_calories'] = $total_calories_average;
            $processedGroupedResults['avg_total_carbs'] = $total_carbs_average;
            $processedGroupedResults['avg_total_protein'] = $total_proteins_average;
            $processedGroupedResults['avg_total_fat'] = $total_fats_average;
        }
        unset($processedGroupedResults);

        $final_results = [];

        //Adding the days which are skipped per month
        foreach ($groupedResults as $key => $groupedResultsRows){
            $date = Carbon::parse($groupedResultsRows['planned_date_all'][0]);
            $yearMonthKey1 = $date->format('Y-m');
            $allDatesInMonth = collect(range(1, (int)date('t', strtotime($yearMonthKey1 . '-01'))))
                ->map(function ($day) use ($yearMonthKey1) {
                    return sprintf('%s-%02d', $yearMonthKey1, $day);
                });
            $missingDates = $allDatesInMonth->diff($groupedResultsRows['planned_date_all'])->values();
            $allDatesFormatted = $missingDates->map(function ($date) {
                return date('d F Y', strtotime($date));
            });

            $final_results[$key]['month'] = $groupedResultsRows['month'];
            $final_results[$key]['planned_percentage'] =
                number_format($groupedResultsRows['planned_percentage'],0).'%';
            $final_results[$key]['avg_total_calories'] =
                floatval(number_format($groupedResultsRows['avg_total_calories'],2));
            $final_results[$key]['avg_total_carbs'] =
                floatval(number_format($groupedResultsRows['avg_total_carbs'],2));
            $final_results[$key]['avg_total_protein'] =
                floatval(number_format($groupedResultsRows['avg_total_protein'],2));
            $final_results[$key]['avg_total_fat'] =
                floatval(number_format($groupedResultsRows['avg_total_fat'],2));
            $final_results[$key]['days_planning_skipped'] = $allDatesFormatted->toArray();
        }
        $return_results = [];
        foreach ($final_results as $final_result){
            $return_results[] = $final_result;
        }

        return response()->json(['data' => $return_results]);
    }
}
