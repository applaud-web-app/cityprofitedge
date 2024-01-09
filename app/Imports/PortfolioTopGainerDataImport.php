<?php

namespace App\Imports;

use App\Models\PortfolioTopGainer;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PortfolioTopGainerDataImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        // Check if the collection is empty or has no data rows
        if ($rows->isEmpty()) {
            // Handle the case when there is no data in the Excel file
            throw new \Exception('The Excel file does not contain any data.');
        }

        try {
            // Filter out rows with at least one non-empty cell
            $filteredRows = $rows->filter(function ($row) {
                return collect($row)->filter(function ($cell) {
                    return !empty($cell);
                })->count() > 0;
            });

            // Process each row of data
            foreach ($filteredRows as $row) {
                DB::beginTransaction();
                // Create and save the Stock model instance with the data
                $portfolioTopGainer = new PortfolioTopGainer();
                $portfolioTopGainer->stock_name = $row['stock_name'];
                $portfolioTopGainer->avg_buy_price = $row['avg_price'];
                $portfolioTopGainer->cmp = $row['cmp'];
                $portfolioTopGainer->change_percentage = $row['change'];
                $portfolioTopGainer->save();
                DB::commit();
            }
        } catch (\Throwable $th) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            throw $th;
        }

    }
}
