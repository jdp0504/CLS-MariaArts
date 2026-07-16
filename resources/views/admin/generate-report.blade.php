@extends('layouts.admin')

@section('title', 'System Reports — Maria Art\'s Loyalty System')

@section('showBack', 'true')

@section('head_scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('styles')
        .page-title { font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.25rem; }
        .page-subtitle { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem; }

        .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.5rem 2rem; margin-bottom: 1.25rem; }
        .card-title { font-weight: 700; font-size: 1rem; margin-bottom: 1rem; }

        .filter-bar { display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap; }
        .filter-bar .field { display: flex; flex-direction: column; gap: 4px; }
        .filter-bar label { font-size: 0.72rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap; }
        .filter-bar input, .filter-bar select {
            padding: 0.5rem 0.7rem; border: 1px solid var(--border); border-radius: var(--radius-sm);
            font-size: 0.82rem; font-family: inherit; background: var(--surface); color: var(--text-primary);
            min-width: 120px;
        }
        .filter-bar input:focus, .filter-bar select:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-subtle); }
        .filter-actions { display: flex; gap: 8px; align-items: center; }
        .btn-gen {
            background: var(--brand); color: #fff; border: none; padding: 0.5rem 1.25rem;
            border-radius: var(--radius-sm); font-weight: 600; font-size: 0.82rem;
            cursor: pointer; font-family: inherit; transition: background 0.2s;
        }
        .btn-gen:hover { background: var(--brand-light); }
        .btn-clear {
            background: transparent; border: 1px solid var(--border); color: var(--text-secondary);
            padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.8rem;
            cursor: pointer; text-decoration: none; font-family: inherit; transition: all 0.2s;
        }
        .btn-clear:hover { background: var(--surface-alt); border-color: var(--text-tertiary); }

        .metrics-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .metric-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 1.25rem; }
        .metric-card .m-val { font-size: 1.6rem; font-weight: 700; display: block; margin-bottom: 2px; }
        .metric-card .m-label { color: var(--text-tertiary); font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em; }
        .m-green { color: var(--accent-green); }
        .m-red { color: var(--danger); }
        .m-brand { color: var(--brand); }

        .tabs { display: flex; background: #e2e8f0; padding: 4px; border-radius: 50px; width: fit-content; margin-bottom: 1.5rem; }
        .tab { padding: 8px 24px; border-radius: 40px; text-decoration: none; color: var(--text-secondary); font-weight: 600; font-size: 0.85rem; transition: 0.2s; }
        .tab.active { background: white; color: var(--text-primary); box-shadow: 0 1px 3px rgba(0,0,0,0.1); }

        .chart-wrap { position: relative; height: 300px; width: 100%; margin-bottom: 1rem; }

        .table-wrap { overflow-x: auto; }
        table.rt { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        table.rt th {
            text-align: left; padding: 0.6rem 0.8rem; border-bottom: 2px solid var(--border);
            color: var(--text-tertiary); font-weight: 600; text-transform: uppercase; font-size: 0.68rem;
            letter-spacing: 0.04em; white-space: nowrap;
        }
        table.rt td { padding: 0.55rem 0.8rem; border-bottom: 1px solid var(--border); }
        table.rt tr:last-child td { border-bottom: none; }
        table.rt tr:hover td { background: var(--surface-alt); }

        .badge { padding: 3px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .badge-earn { background: var(--accent-green-bg); color: #065f46; }
        .badge-redeem { background: var(--danger-bg); color: #991b1b; }

        .empty-state { color: var(--text-tertiary); font-size: 0.85rem; padding: 2rem 0; text-align: center; }

        .summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
        .stat-row { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; margin-bottom: 8px; border-radius: var(--radius-sm); border: 1px solid var(--border); border-left: 4px solid var(--brand); background: var(--surface); }
        .stat-row:last-child { margin-bottom: 0; }
        .stat-row .si h5 { font-size: 0.85rem; margin: 0; }
        .stat-row .si span { font-size: 0.72rem; color: var(--text-tertiary); }
        .stat-row .sv { text-align: right; }
        .stat-row .sv .num { display: block; font-weight: 700; font-size: 1rem; }
        .stat-row .sv .unit { font-size: 0.7rem; color: var(--text-secondary); }
        .bl-purchase { border-left-color: var(--accent-green); }
        .bl-redeem { border-left-color: var(--accent-purple); }
        .bl-referral { border-left-color: var(--accent-amber); }
        .bl-total { border-left-color: var(--brand); }
        .c-green { color: var(--accent-green); }
        .c-purple { color: var(--accent-purple); }
        .c-amber { color: var(--accent-amber); }
        .c-blue { color: var(--brand); }

        .rank-circle { width: 24px; height: 24px; background: var(--brand-subtle); color: var(--brand); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 700; margin-right: 6px; }

        @media (max-width: 768px) {
            .filter-bar { flex-direction: column; align-items: stretch; }
            .filter-bar .field { min-width: 100%; }
            .filter-bar input, .filter-bar select { min-width: 100%; }
            .metrics-grid { grid-template-columns: repeat(2, 1fr); }
            .summary-grid { grid-template-columns: 1fr; }
            .tabs { width: 100%; }
            .tab { flex: 1; text-align: center; font-size: 0.78rem; padding: 8px 12px; }
        }
@endsection

@section('content')
    <h1 class="page-title">System Reports</h1>
    <p class="page-subtitle">View comprehensive data reports on customer loyalty points, reward redemptions, and member activity.</p>

    <form method="GET" action="/admin/generate-report" class="card">
        <div class="card-title">Report Filters</div>
        <div class="filter-bar">
            <div class="field">
                <label>From Date</label>
                <input type="date" name="from_date" value="{{ $fromDate }}">
            </div>
            <div class="field">
                <label>To Date</label>
                <input type="date" name="to_date" value="{{ $toDate }}">
            </div>
            <div class="field">
                <label>Customer</label>
                <select name="customer_id">
                    <option value="all" {{ $custId === 'all' ? 'selected' : '' }}>All Customers</option>
                    @foreach ($allCustomers as $c)
                    <option value="{{ $c->customerID }}" {{ $custId == $c->customerID ? 'selected' : '' }}>{{ $c->customerName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Type</label>
                <select name="type">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All</option>
                    <option value="earn" {{ $type === 'earn' ? 'selected' : '' }}>Earn Only</option>
                    <option value="redeem" {{ $type === 'redeem' ? 'selected' : '' }}>Redeem Only</option>
                </select>
            </div>
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="filter-actions">
                <a href="/admin/generate-report" class="btn-clear">Clear Filters</a>
                <button type="submit" class="btn-gen">Refresh Data</button>
            </div>
        </div>
    </form>

    <div class="metrics-grid">
        <div class="metric-card">
            <span class="m-val m-brand">{{ number_format($stats['active']) }}</span>
            <span class="m-label">Active Customers</span>
        </div>
        <div class="metric-card">
            <span class="m-val m-brand">{{ number_format($stats['tx_count']) }}</span>
            <span class="m-label">Total Transactions</span>
        </div>
        <div class="metric-card">
            <span class="m-val m-green">{{ number_format($stats['earned']) }}</span>
            <span class="m-label">Points Earned</span>
        </div>
        <div class="metric-card">
            <span class="m-val m-red">{{ number_format($stats['spent']) }}</span>
            <span class="m-label">Points Spent</span>
        </div>
    </div>

    @php
        $q = "from_date=$fromDate&to_date=$toDate&customer_id=$custId&type=$type";
    @endphp
    <div class="tabs">
        <a href="?tab=activity&{{ $q }}" class="tab {{ $tab === 'activity' ? 'active' : '' }}">Customer Activity</a>
        <a href="?tab=redemptions&{{ $q }}" class="tab {{ $tab === 'redemptions' ? 'active' : '' }}">Redemptions</a>
        <a href="?tab=points&{{ $q }}" class="tab {{ $tab === 'points' ? 'active' : '' }}">Points Summary</a>
    </div>

    @if ($tab === 'activity')
        <div class="card">
            <div class="card-title">Trends: Earned vs Spent</div>
            @if (empty($chartLabels))
                <div class="empty-state">No data available for the selected filters.</div>
            @else
            <div class="chart-wrap">
                <canvas id="reportChart"></canvas>
            </div>
            @endif
        </div>

        <div class="card">
            <div class="card-title">Activity Log</div>
            <p style="margin:-0.5rem 0 1rem; font-size:0.82rem; color:var(--text-secondary);">
                Showing records from {{ date('d M Y', strtotime($fromDate)) }} to {{ date('d M Y', strtotime($toDate)) }}
            </p>
            @if ($activityLog->isEmpty())
                <div class="empty-state">No activity found for the selected filters.</div>
            @else
            <div class="table-wrap">
                <table class="rt">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Points</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activityLog as $row)
                        <tr>
                            <td>{{ $row->created_at }}</td>
                            <td><strong>{{ $row->customer }}</strong></td>
                            <td><span class="badge {{ $row->type === 'EARN' ? 'badge-earn' : 'badge-redeem' }}">{{ $row->type }}</span></td>
                            <td style="font-weight:700; color:{{ $row->type === 'EARN' ? '#065f46' : '#991b1b' }}">
                                {{ $row->type === 'EARN' ? '+' : '-' }}{{ $row->points }}
                            </td>
                            <td>{{ $row->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    @endif

    @if ($tab === 'redemptions')
        <div class="card">
            <div class="card-title">Most Popular Rewards</div>
            @if (empty($chartLabels))
                <div class="empty-state">No redemptions found for the selected period.</div>
            @else
            <div class="chart-wrap">
                <canvas id="reportChart"></canvas>
            </div>
            @endif
        </div>

        <div class="card">
            <div class="card-title">Redemption History</div>
            @if ($redemptions->isEmpty())
                <div class="empty-state">No redemption history found.</div>
            @else
            <div class="table-wrap">
                <table class="rt">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Reward</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($redemptions as $r)
                        <tr>
                            <td>{{ $r->redeemedDate }}</td>
                            <td><strong>{{ $r->customer->customerName ?? 'Unknown' }}</strong></td>
                            <td>{{ $r->reward->rewardName ?? 'Unknown' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    @endif

    @if ($tab === 'points')
        <div class="card">
            <div class="card-title">Top Points Earners</div>
            <p style="margin:-0.5rem 0 1rem; font-size:0.82rem; color:var(--text-secondary);">
                Customers ranked by points earned within the selected date range
            </p>
            @if ($topEarners->isEmpty())
                <div class="empty-state">No data available.</div>
            @else
            <div class="table-wrap">
                <table class="rt">
                    <thead>
                        <tr>
                            <th width="60">Rank</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th style="text-align:right;">Points Earned</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topEarners as $i => $c)
                        <tr>
                            <td><span class="rank-circle">{{ $i + 1 }}</span></td>
                            <td><strong>{{ $c->customerName }}</strong></td>
                            <td style="color:var(--text-secondary);">{{ $c->email ?? 'N/A' }}</td>
                            <td style="text-align:right; font-weight:700;">{{ number_format($c->points) }} pts</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <div class="summary-grid">
            <div class="card" style="margin-bottom:0;">
                <div class="card-title">Points Distribution</div>
                <p style="margin:-0.5rem 0 1rem; font-size:0.82rem; color:var(--text-secondary);">Breakdown by transaction type</p>

                <div class="stat-row bl-purchase">
                    <div class="si"><h5>Purchase</h5><span>Regular store purchases</span></div>
                    <div class="sv"><span class="num c-green">{{ number_format($summaryMetrics['purchase']['pts']) }}</span><span class="unit">points</span></div>
                </div>
                <div class="stat-row bl-referral">
                    <div class="si"><h5>Referral</h5><span>Inviting new users</span></div>
                    <div class="sv"><span class="num c-amber">{{ number_format($summaryMetrics['referral']['pts']) }}</span><span class="unit">points</span></div>
                </div>
                <div class="stat-row bl-redeem">
                    <div class="si"><h5>Redemption</h5><span>Rewards claimed</span></div>
                    <div class="sv"><span class="num c-purple">{{ number_format($summaryMetrics['redeem']['pts']) }}</span><span class="unit">points</span></div>
                </div>
            </div>

            <div class="card" style="margin-bottom:0;">
                <div class="card-title">Activity Metrics</div>
                <p style="margin:-0.5rem 0 1rem; font-size:0.82rem; color:var(--text-secondary);">Transaction volume statistics</p>

                <div class="stat-row bl-total" style="background:#f0f9ff;">
                    <div class="si"><h5 style="color:#1e40af;">Total Transactions</h5></div>
                    <div class="sv"><span class="num c-blue">{{ number_format($summaryMetrics['total_tx']) }}</span></div>
                </div>
                <div class="stat-row bl-purchase">
                    <div class="si"><h5>Purchase Transactions</h5></div>
                    <div class="sv"><span class="num c-green">{{ number_format($summaryMetrics['purchase']['cnt']) }}</span></div>
                </div>
                <div class="stat-row bl-referral">
                    <div class="si"><h5>Referral Transactions</h5></div>
                    <div class="sv"><span class="num c-amber">{{ number_format($summaryMetrics['referral']['cnt']) }}</span></div>
                </div>
                <div class="stat-row bl-redeem">
                    <div class="si"><h5>Redemption Transactions</h5></div>
                    <div class="sv"><span class="num c-purple">{{ number_format($summaryMetrics['redeem']['cnt']) }}</span></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Customer Points Breakdown</div>
            @if ($pointsData->isEmpty())
                <div class="empty-state">No customer data found for the selected filters.</div>
            @else
            <div class="table-wrap">
                <table class="rt">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Points Purchased</th>
                            <th>Points Redeemed</th>
                            <th>Referral Points</th>
                            <th>Current Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pointsData as $row)
                        <tr>
                            <td><strong>{{ $row->customerName }}</strong></td>
                            <td>{{ number_format($row->totalPurchased) }}</td>
                            <td>{{ number_format($row->totalRedeemed) }}</td>
                            <td>{{ number_format($row->refPoints) }}</td>
                            <td><strong>{{ number_format($row->currentPoints) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    @endif

    @if (!empty($chartLabels))
    <script>
        var ctx = document.getElementById('reportChart').getContext('2d');
        var chartType = '{{ $chartType }}';
        var labels = @json($chartLabels);
        var data1  = @json($chartData1);
        var data2  = @json($chartData2);

        var config;

        if (chartType === 'line') {
            config = {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Points Earned', data: data1, borderColor: '#059669', backgroundColor: 'rgba(5,150,105,0.1)', tension: 0.3, fill: true },
                        { label: 'Points Spent',  data: data2, borderColor: '#dc2626', backgroundColor: 'rgba(220,38,38,0.1)', tension: 0.3, fill: true }
                    ]
                },
                options: { maintainAspectRatio: false, responsive: true, plugins: { legend: { position: 'bottom' } } }
            };
        } else if (chartType === 'doughnut') {
            var colors = ['#0284c7','#d97706','#059669','#dc2626','#7c3aed','#ec4899','#6366f1','#14b8a6'];
            config = {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{ data: data1, backgroundColor: colors.slice(0, labels.length) }]
                },
                options: { maintainAspectRatio: false, responsive: true, plugins: { legend: { position: 'bottom' } } }
            };
        }

        if (config) { new Chart(ctx, config); }
    </script>
    @endif
@endsection
