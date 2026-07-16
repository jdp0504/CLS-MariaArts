<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Reward;
use App\Models\Notification;
use App\Models\PurchaseTransaction;
use App\Models\Redemption;
use App\Models\Referral;
use App\Mail\NotificationMail;

class AdminController extends Controller
{
    public function manageMembership(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $search = $request->input('search');
        $status = $request->input('status', 'all');

        $query = Customer::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customerID', 'LIKE', "%$search%")
                  ->orWhere('customerName', 'LIKE', "%$search%");
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $customers = $query->orderBy('customerName')->get();

        $totalActive = Customer::where('status', 'active')->count();
        $totalInactive = Customer::where('status', 'inactive')->count();

        return view('admin.manage-membership', compact('customers', 'search', 'status', 'totalActive', 'totalInactive'));
    }

    public function changeStatus(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $request->validate([
            'customerID' => 'required|string|exists:Customer,customerID',
        ]);

        $customer = Customer::findOrFail($request->customerID);

        if ($customer->status === 'active') {
            $customer->update([
                'status'     => 'inactive',
                'archivedAt' => now(),
            ]);

            $action = 'archived';
        } else {
            $customer->update([
                'status'     => 'active',
                'archivedAt' => null,
            ]);

            $action = 'reactivated';
        }

        $adminName = session('username');

        return redirect('/admin/manage-membership')
            ->with('success', "{$customer->customerName} has been {$action} by {$adminName}.");
    }

    // ─── Manage Rewards ───

    public function manageRewards(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $rewards = Reward::orderBy('rewardID')->get();
        $editReward = null;

        if ($request->has('edit')) {
            $editReward = Reward::find($request->edit);
        }

        $totalActive = Reward::where('status', 'active')->count();
        $totalInactive = Reward::where('status', 'inactive')->count();

        return view('admin.manage-rewards', compact('rewards', 'editReward', 'totalActive', 'totalInactive'));
    }

    public function addReward(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $request->validate([
            'rewardName'     => 'required|string|max:150',
            'description'    => 'nullable|string',
            'pointRequired'  => 'required|integer|min:1',
            'stock'          => 'required|integer|min:0',
            'status'         => 'required|in:active,inactive',
        ], [
            'rewardName.required'    => 'Blank Fields',
            'pointRequired.required' => 'Blank Fields',
            'pointRequired.min'      => 'Invalid Input',
            'stock.required'         => 'Blank Fields',
            'stock.min'              => 'Invalid Input',
            'status.required'        => 'Blank Fields',
        ]);

        $last = Reward::orderBy('rewardID', 'desc')->first();
        $num = $last ? (int) substr($last->rewardID, 3) + 1 : 1;
        $rewardID = 'RWD' . str_pad($num, 3, '0', STR_PAD_LEFT);

        Reward::create([
            'rewardID'       => $rewardID,
            'rewardName'     => $request->rewardName,
            'description'    => $request->description,
            'pointRequired'  => $request->pointRequired,
            'stock'          => $request->stock,
            'status'         => $request->status,
        ]);

        return redirect('/admin/manage-rewards')
            ->with('success', "Reward \"{$request->rewardName}\" has been added successfully.");
    }

    public function updateReward(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $request->validate([
            'rewardID'       => 'required|string|exists:Reward,rewardID',
            'rewardName'     => 'required|string|max:150',
            'description'    => 'nullable|string',
            'pointRequired'  => 'required|integer|min:1',
            'stock'          => 'required|integer|min:0',
            'status'         => 'required|in:active,inactive',
        ], [
            'rewardName.required'    => 'Blank Fields',
            'pointRequired.required' => 'Blank Fields',
            'pointRequired.min'      => 'Invalid Input',
            'stock.required'         => 'Blank Fields',
            'stock.min'              => 'Invalid Input',
            'status.required'        => 'Blank Fields',
        ]);

        $reward = Reward::findOrFail($request->rewardID);
        $reward->update([
            'rewardName'     => $request->rewardName,
            'description'    => $request->description,
            'pointRequired'  => $request->pointRequired,
            'stock'          => $request->stock,
            'status'         => $request->status,
        ]);

        return redirect('/admin/manage-rewards')
            ->with('success', "Reward \"{$request->rewardName}\" has been updated successfully.");
    }

    public function archiveReward(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $request->validate([
            'rewardID' => 'required|string|exists:Reward,rewardID',
        ]);

        $reward = Reward::findOrFail($request->rewardID);

        if ($reward->status === 'active') {
            $reward->update(['status' => 'inactive']);
            $action = 'archived';
        } else {
            $reward->update(['status' => 'active']);
            $action = 'reactivated';
        }

        return redirect('/admin/manage-rewards')
            ->with('success', "Reward \"{$reward->rewardName}\" has been {$action}.");
    }

    // ─── Generate Notification ───

    private function replaceTags($text, $customer)
    {
        $map = [
            '{name}'         => $customer->customerName,
            '{phone}'        => $customer->phoneNumber ?? 'N/A',
            '{points}'       => (string) ($customer->currentPoints ?? 0),
            '{referralCode}' => $customer->referralCode ?? 'N/A',
            '{email}'        => $customer->email ?? '',
        ];
        return str_replace(array_keys($map), array_values($map), $text);
    }

    private function handleAttachment($request)
    {
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'])) {
                return ['error' => 'Invalid file type. Allowed: jpg, jpeg, png, pdf, doc, docx, txt'];
            }
            $path = $file->store('uploads', 'public');
            return ['path' => $path];
        }
        return ['path' => null];
    }

    public function showNotifForm(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        // Handle draft deletion
        if ($request->has('delete_draft')) {
            Notification::where('notificationID', $request->delete_draft)
                ->where('adminID', session('user_id'))
                ->where('status', 'draft')
                ->delete();
            return redirect('/admin/generate-notification')->with('success', 'Draft deleted.');
        }

        $filterType = $request->input('filter_type', 'all');
        $filterMonth = $request->input('filter_month', date('m'));

        // All active customers for the recipient panel
        $allCustomers = Customer::where('status', 'active')
            ->orderBy('customerName')
            ->get();

        // Determine pre-selected IDs based on filter
        $preSelectedIds = collect();
        if ($request->isMethod('post') || $request->has('load_draft')) {
            $filterType = $request->input('filter_type', $filterType);
            $filterMonth = $request->input('filter_month', $filterMonth);

            if ($filterType === 'birthday') {
                $preSelectedIds = Customer::where('status', 'active')
                    ->whereNotNull('birthDate')
                    ->whereMonth('birthDate', $filterMonth)
                    ->pluck('customerID');
            } else {
                $preSelectedIds = $allCustomers->pluck('customerID');
            }
        }

        $drafts = Notification::where('status', 'draft')
            ->where('adminID', session('user_id'))
            ->orderBy('creationDate', 'desc')
            ->get();

        $loadDraft = null;
        if ($request->has('load_draft')) {
            $loadDraft = Notification::find($request->load_draft);
        }

        $previewed = $request->isMethod('post') || $request->has('load_draft');

        return view('admin.generate-notification', compact(
            'filterType', 'filterMonth', 'allCustomers', 'preSelectedIds',
            'drafts', 'loadDraft', 'previewed'
        ));
    }

    public function saveOrSendNotif(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $request->validate([
            'subject'        => 'required|string',
            'messageContent' => 'required|string',
            'action'         => 'required|in:draft,send',
        ], [
            'subject.required'        => 'Invalid Message',
            'messageContent.required' => 'Invalid Message',
            'action.required'         => 'Invalid Message',
        ]);

        if (trim($request->subject) === '' || trim($request->messageContent) === '') {
            return redirect('/admin/generate-notification')
                ->with('error', 'Invalid Message');
        }

        // Handle file upload
        $attachmentResult = $this->handleAttachment($request);
        if (isset($attachmentResult['error'])) {
            return redirect('/admin/generate-notification')->with('error', $attachmentResult['error']);
        }
        $attachmentPath = $attachmentResult['path'];

        $adminID = session('user_id');

        // ─── DRAFT ───
        if ($request->action === 'draft') {
            $draftId = $request->input('draft_id');

            $data = [
                'adminID'        => $adminID,
                'customerID'     => null,
                'subject'        => $request->subject,
                'messageContent' => $request->messageContent,
                'filterType'     => $request->input('filter_type', 'all'),
                'filterValue'    => $request->input('filter_month', date('m')),
                'creationDate'   => now()->toDateString(),
                'status'         => 'draft',
            ];

            if ($attachmentPath) {
                $data['attachment'] = $attachmentPath;
            } elseif ($request->input('existing_attachment')) {
                $data['attachment'] = $request->input('existing_attachment');
            }

            if ($draftId) {
                Notification::where('notificationID', $draftId)->update($data);
                $msg = 'Draft updated successfully.';
            } else {
                Notification::create($data);
                $msg = 'Draft saved successfully.';
            }

            return redirect('/admin/generate-notification')->with('success', $msg);
        }

        // ─── SEND ───
        $recipientIds = $request->input('recipients', []);
        if (empty($recipientIds)) {
            return redirect('/admin/generate-notification')->with('error', 'No recipients selected.');
        }

        $recipients = Customer::whereIn('customerID', $recipientIds)
            ->where('status', 'active')
            ->get();

        if ($recipients->isEmpty()) {
            return redirect('/admin/generate-notification')->with('error', 'No valid recipients.');
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($recipients as $customer) {
            $personalizedSub = $this->replaceTags($request->subject, $customer);
            $personalizedMsg = $this->replaceTags($request->messageContent, $customer);

            $emailSent = false;
            if (filter_var($customer->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($customer->email)->send(new NotificationMail(
                        $personalizedSub,
                        $personalizedMsg,
                        $attachmentPath ? storage_path('app/public/' . $attachmentPath) : null
                    ));
                    $emailSent = true;
                } catch (\Exception $e) {
                    $emailSent = false;
                }
            }

            Notification::create([
                'adminID'        => $adminID,
                'customerID'     => $customer->customerID,
                'subject'        => $personalizedSub,
                'messageContent' => $personalizedMsg,
                'attachment'     => $attachmentPath,
                'filterType'     => $request->input('filter_type', 'all'),
                'filterValue'    => $request->input('filter_month', null),
                'creationDate'   => now()->toDateString(),
                'status'         => $emailSent ? 'sent' : 'failed',
            ]);

            if ($emailSent) { $successCount++; } else { $failCount++; }
        }

        $admin = session('username');
        if ($successCount > 0) {
            $msg = "Notification sent to {$successCount} recipient(s) by {$admin}.";
            if ($failCount > 0) $msg .= " {$failCount} failed.";
            return redirect('/admin/generate-notification')->with('success', $msg);
        }

        return redirect('/admin/generate-notification')
            ->with('error', 'All emails failed. Check recipient email addresses and SMTP configuration.');
    }

    // ─── SRS_UCD_900: View Report ───

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

        // ─── Header Stats ───
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

        // ─── Shared chart vars ───
        $chartLabels = [];
        $chartData1  = [];
        $chartData2  = [];
        $chartType   = 'line';

        // ─── A1: Customer Activity (per-transaction timeline) ───
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

        // ─── A2: Redemptions ───
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

        // ─── A3: Points Summary ───
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

            // Summary metrics
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

            // Top earners (by points earned in date range)
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
