<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\PurchaseTransaction;
use App\Models\Redemption;
use App\Models\Referral;

class ViewReportController extends Controller
{
    public function showReport(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $fromDate = $request->input('from_date', now()->startOfMonth()->toDateString());
        $toDate   = $request->input('to_date', now()->toDateString());
        $custId   = $request->input('customer_id', 'all');
        $type     = $request->input('type', 'all');
        $tab      = $request->input('tab', 'activity');

        $allCustomers = Customer::where('status', 'active')
            ->orderBy('customerName')
            ->get(['customerID', 'customerName']);

        $earnStatsQ = PurchaseTransaction::whereBetween('transactionDate', [$fromDate, $toDate]);
        if ($custId !== 'all') $earnStatsQ->where('customerID', $custId);
        $earned   = $earnStatsQ->sum('pointEarned');
        $earnCnt  = $earnStatsQ->count();

        $spentStatsQ = DB::table('Redemption')
            ->join('Reward', 'Redemption.rewardID', '=', 'Reward.rewardID')
            ->whereBetween('Redemption.redeemedDate', [$fromDate, $toDate]);
        if ($custId !== 'all') $spentStatsQ->where('Redemption.customerID', $custId);
        $spent   = (int) $spentStatsQ->sum('Reward.pointRequired');
        $spentCnt = $spentStatsQ->count();

        $stats = [
            'active'   => Customer::where('status', 'active')->count(),
            'tx_count' => $earnCnt + $spentCnt,
            'earned'   => $earned,
            'spent'    => $spent,
        ];

        $chartLabels = [];
        $chartData1  = [];
        $chartData2  = [];
        $chartType   = 'line';

        $activityLog = collect();

        if ($tab === 'activity') {
            $earn = collect();
            if ($type === 'all' || $type === 'earn') {
                $earn = PurchaseTransaction::with('customer')
                    ->whereBetween('transactionDate', [$fromDate, $toDate])
                    ->when($custId !== 'all', fn($q) => $q->where('customerID', $custId))
                    ->get()
                    ->map(fn($t) => (object) [
                        'created_at' => $t->transactionDate,
                        'customer'   => $t->customer->customerName ?? 'Unknown',
                        'type'       => 'EARN',
                        'points'     => $t->pointEarned,
                        'description'=> "Purchase RM{$t->totalPrice}",
                    ]);
            }

            $redeem = collect();
            if ($type === 'all' || $type === 'redeem') {
                $redeem = Redemption::with(['customer', 'reward'])
                    ->whereBetween('redeemedDate', [$fromDate, $toDate])
                    ->when($custId !== 'all', fn($q) => $q->where('customerID', $custId))
                    ->get()
                    ->map(fn($r) => (object) [
                        'created_at' => $r->redeemedDate,
                        'customer'   => $r->customer->customerName ?? 'Unknown',
                        'type'       => 'REDEEM',
                        'points'     => $r->reward->pointRequired ?? 0,
                        'description'=> "Redeemed " . ($r->reward->rewardName ?? 'Unknown'),
                    ]);
            }

            $activityLog = $earn->merge($redeem)->sortByDesc('created_at');

            $dates = [];
            foreach ($activityLog as $row) {
                $d = $row->created_at;
                if (!isset($dates[$d])) $dates[$d] = ['earn' => 0, 'redeem' => 0];
                if ($row->type === 'EARN') $dates[$d]['earn'] += $row->points;
                else $dates[$d]['redeem'] += $row->points;
            }
            ksort($dates);
            $chartLabels = array_keys($dates);
            foreach ($dates as $d) { $chartData1[] = $d['earn']; $chartData2[] = $d['redeem']; }
            $chartType = 'line';
        }

        $redemptions    = collect();
        $popularRewards = collect();

        if ($tab === 'redemptions') {
            $redemptions = Redemption::with(['customer', 'reward'])
                ->whereBetween('redeemedDate', [$fromDate, $toDate])
                ->when($custId !== 'all', fn($q) => $q->where('customerID', $custId))
                ->orderBy('redeemedDate', 'desc')
                ->get();

            $popularRewards = $redemptions->groupBy('rewardID')->map(fn($rs) => (object) [
                'rewardName' => $rs->first()->reward->rewardName ?? 'Unknown',
                'count'      => $rs->count(),
            ])->sortByDesc('count');

            $chartLabels = $popularRewards->pluck('rewardName')->values()->toArray();
            $chartData1  = $popularRewards->pluck('count')->values()->toArray();
            $chartType   = 'doughnut';
        }

        $pointsData     = collect();
        $summaryMetrics = [];
        $topEarners     = collect();

        if ($tab === 'points') {
            $pointsQ = Customer::where('status', 'active');
            if ($custId !== 'all') $pointsQ->where('customerID', $custId);
            $pointsData = $pointsQ->get()->map(fn($c) => (object) [
                'customerName'   => $c->customerName,
                'totalPurchased' => PurchaseTransaction::where('customerID', $c->customerID)
                    ->whereBetween('transactionDate', [$fromDate, $toDate])->sum('pointEarned'),
                'totalRedeemed'  => (int) DB::table('Redemption')
                    ->join('Reward', 'Redemption.rewardID', '=', 'Reward.rewardID')
                    ->where('Redemption.customerID', $c->customerID)
                    ->whereBetween('Redemption.redeemedDate', [$fromDate, $toDate])
                    ->sum('Reward.pointRequired'),
                'refPoints'      => Referral::where('customerID', $c->customerID)
                    ->whereBetween('dateRef', [$fromDate, $toDate])->sum('pointGranted'),
                'currentPoints'  => $c->currentPoints ?? 0,
            ]);

            $pPts = PurchaseTransaction::whereBetween('transactionDate', [$fromDate, $toDate])
                ->when($custId !== 'all', fn($q) => $q->where('customerID', $custId));
            $pSum = $pPts->sum('pointEarned'); $pCnt = $pPts->count();

            $rPts = Referral::whereBetween('dateRef', [$fromDate, $toDate])
                ->when($custId !== 'all', fn($q) => $q->where('customerID', $custId));
            $rSum = $rPts->sum('pointGranted'); $rCnt = $rPts->count();

            $rdQ = DB::table('Redemption')
                ->join('Reward', 'Redemption.rewardID', '=', 'Reward.rewardID')
                ->whereBetween('Redemption.redeemedDate', [$fromDate, $toDate])
                ->when($custId !== 'all', fn($q) => $q->where('Redemption.customerID', $custId));
            $rdSum = (int) $rdQ->sum('Reward.pointRequired'); $rdCnt = $rdQ->count();

            $summaryMetrics = [
                'purchase' => ['pts' => $pSum, 'cnt' => $pCnt],
                'referral' => ['pts' => $rSum, 'cnt' => $rCnt],
                'redeem'   => ['pts' => $rdSum, 'cnt' => $rdCnt],
                'total_tx' => $pCnt + $rCnt + $rdCnt,
            ];

            $topEarners = DB::table('Customer')
                ->leftJoin('PurchaseTransaction', function ($j) use ($fromDate, $toDate) {
                    $j->on('Customer.customerID', '=', 'PurchaseTransaction.customerID')
                      ->whereBetween('PurchaseTransaction.transactionDate', [$fromDate, $toDate]);
                })
                ->selectRaw('Customer.customerID, Customer.customerName, Customer.email, COALESCE(SUM(PurchaseTransaction.pointEarned), 0) as points')
                ->where('Customer.status', 'active')
                ->when($custId !== 'all', fn($q) => $q->where('Customer.customerID', $custId))
                ->groupBy('Customer.customerID', 'Customer.customerName', 'Customer.email')
                ->orderByDesc('points')
                ->limit(10)
                ->get();
        }

        return view('admin.generate-report', compact(
            'fromDate', 'toDate', 'custId', 'type', 'tab', 'allCustomers',
            'stats', 'activityLog', 'chartLabels', 'chartData1', 'chartData2', 'chartType',
            'redemptions', 'popularRewards', 'pointsData', 'summaryMetrics', 'topEarners'
        ));
    }
}
