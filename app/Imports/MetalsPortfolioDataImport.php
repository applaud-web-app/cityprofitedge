<?php

namespace App\Imports;

use App\Models\MetalsPortfolio;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\PoolingAccountPortfolio;
use App\Models\User;
use Carbon\Carbon;

class MetalsPortfolioDataImport implements ToCollection, WithHeadingRow
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

        // Filter out rows with at least one non-empty cell
        $filteredRows = $rows->filter(function ($row) {
            return collect($row)->filter(function ($cell) {
                return !empty($cell);
            })->count() > 0;
        });
        try {
            // Process each row of data
            foreach ($filteredRows as $row) {
                DB::beginTransaction();

                if (empty($row['client_code'])) {
                    DB::commit();
                    continue;
                }

                $user = User::select('id', 'user_code')->where('user_code', $row['client_code'])->first();

                if (empty($user)) {
                    continue;
                }

                if (empty($row['pooling_broker_name']) && empty($row['pooling_broker_code'])) {
                    DB::commit();
                    continue;
                }

                if (!empty($row['pooling_broker_code'])) {
                    $poolingBrokerPortfolio = PoolingAccountPortfolio::where('broker_code', $row['pooling_broker_code'])->first();
                } else {
                    $poolingBrokerPortfolio = new PoolingAccountPortfolio();
                    $poolingBrokerPortfolio->broker_name = $row['pooling_broker_name'];
                    $poolingBrokerPortfolio->broker_code = $this->uniquePoolingBrokerCode();
                    $poolingBrokerPortfolio->user_id = $user->id;
                    $poolingBrokerPortfolio->save();
                }

                if (empty($poolingBrokerPortfolio)) {
                    continue;
                }

                // Process each row of data
                DB::beginTransaction();
                // Create and save the Stock model instance with the data
                $metalsPortfolio = new MetalsPortfolio();
                $metalsPortfolio->broker_name = $row['broker_name'];
                $metalsPortfolio->stock_name = $row['stock_name'];
                $metalsPortfolio->quantity = $row['quantity'];
                $metalsPortfolio->buy_date = $row['buy_date'];
                $metalsPortfolio->buy_price = $row['buy_price'];
                $metalsPortfolio->cmp = $row['cmp'];
                $metalsPortfolio->current_value = $row['current_value'];
                $metalsPortfolio->profit_loss = $row['profit_loss'];
                $metalsPortfolio->current_value = $row['sector'];
                $metalsPortfolio->save();
            }
        } catch (\Throwable $th) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            throw $th;
        }
    }

    // create method to create a unique pooling broker code with timestamp string with seconds.
    public function uniquePoolingBrokerCode()
    {
        $poolingBrokerCode = 'PB' . Carbon::now()->format('YmdHisv');
        $poolingBrokerPortfolio = PoolingAccountPortfolio::where('broker_code', $poolingBrokerCode)->first();
        if ($poolingBrokerPortfolio) {
            $this->uniquePoolingBrokerCode();
        } else {
            return $poolingBrokerCode;
        }
    }
}
