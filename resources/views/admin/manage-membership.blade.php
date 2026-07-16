@extends('layouts.admin')

@section('title', 'Manage Membership Status — Maria Art\'s')

@section('styles')
        .container { max-width: 1200px; }
        .page-header { margin-bottom: 1.75rem; }
        .page-header h1 { font-size: 1.65rem; font-weight: 800; letter-spacing: -0.03em; margin-bottom: 0.3rem; }
        .page-header p { color: var(--text-secondary); font-size: 0.92rem; }

        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
        .stat-box { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem 1.5rem; }
        .stat-box .label { font-size: 0.75rem; color: var(--text-tertiary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-box .value { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; }
        .stat-box .value.active { color: var(--accent-green); }
        .stat-box .value.inactive { color: var(--text-tertiary); }

        .search-section { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap; }
        .search-section .field { display: flex; flex-direction: column; gap: 0.25rem; }
        .search-section label { font-size: 0.78rem; font-weight: 600; color: var(--text-secondary); }
        .search-section input, .search-section select {
            padding: 0.6rem 0.85rem; font-size: 0.88rem; font-family: inherit;
            border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            background: var(--surface-alt); outline: none; transition: border-color .2s;
        }
        .search-section input:focus, .search-section select:focus { border-color: var(--brand); }
        .search-section input { min-width: 220px; }
        .search-btn {
            padding: 0.6rem 1.25rem; background: var(--brand); color: #fff;
            border: none; border-radius: var(--radius-sm); font-weight: 600;
            font-size: 0.85rem; font-family: inherit; cursor: pointer; transition: background .2s;
        }
        .search-btn:hover { background: var(--brand-light); }
        .clear-btn {
            padding: 0.6rem 1.25rem; background: transparent; color: var(--text-secondary);
            border: 1px solid var(--border); border-radius: var(--radius-sm); font-weight: 600;
            font-size: 0.85rem; font-family: inherit; cursor: pointer; text-decoration: none;
            transition: background .2s;
        }
        .clear-btn:hover { background: var(--surface-alt); }

        .table-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--surface-alt); }
        th { text-align: left; padding: 0.85rem 1.25rem; font-size: 0.78rem; font-weight: 700; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 1px solid var(--border); }
        td { padding: 0.85rem 1.25rem; font-size: 0.88rem; border-bottom: 1px solid var(--border); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--surface-alt); }
        .badge {
            display: inline-flex; padding: 0.25rem 0.75rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .badge-active { background: var(--accent-green-bg); color: #065f46; }
        .badge-inactive { background: var(--accent-red-bg); color: #991b1b; }
        .btn-change {
            padding: 0.45rem 0.9rem; border: none; border-radius: var(--radius-sm);
            font-size: 0.78rem; font-weight: 700; font-family: inherit;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-change.active { background: var(--accent-orange-bg); color: var(--accent-orange); }
        .btn-change.active:hover { background: var(--accent-orange); color: #fff; }
        .btn-change.inactive { background: var(--brand-subtle); color: var(--brand); }
        .btn-change.inactive:hover { background: var(--brand); color: #fff; }
        .empty { text-align: center; padding: 3rem 1.5rem; color: var(--text-tertiary); font-size: 0.9rem; }

        @media (max-width:860px) {
            .stats-row { grid-template-columns: 1fr; }
            .search-section { flex-direction: column; }
            .search-section input { min-width: 100%; }
            th, td { padding: 0.65rem 0.75rem; }
        }
@endsection

@section('showBack', 'true')

@section('content')
    <div class="page-header">
        <h1>Manage Membership Status</h1>
        <p>Browse customer profiles, filter by name or ID, and archive inactive accounts.</p>
    </div>

    <div class="stats-row">
        <div class="stat-box">
            <span class="label">Total Members</span>
            <span class="value">{{ $totalActive + $totalInactive }}</span>
        </div>
        <div class="stat-box">
            <span class="label">Active</span>
            <span class="value active">{{ $totalActive }}</span>
        </div>
        <div class="stat-box">
            <span class="label">Inactive</span>
            <span class="value inactive">{{ $totalInactive }}</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="search-section">
        <form method="GET" action="/admin/manage-membership" style="display:flex;gap:0.75rem;align-items:flex-end;flex-wrap:wrap;width:100%;">
            <div class="field">
                <label>Search Name or ID</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Type name or Customer ID...">
            </div>
            <div class="field">
                <label>Status</label>
                <select name="status">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="search-btn">Search</button>
            <a href="/admin/manage-membership" class="clear-btn">Clear</a>
        </form>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Points</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td style="font-weight:600;font-size:0.82rem;">{{ $customer->customerID }}</td>
                        <td>{{ $customer->customerName }}</td>
                        <td style="color:var(--text-secondary);">{{ $customer->email }}</td>
                        <td>{{ $customer->currentPoints }}</td>
                        <td>
                            <span class="badge {{ $customer->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                {{ $customer->status ?? 'unknown' }}
                            </span>
                        </td>
                        <td>
                            <button type="button"
                                class="btn-change {{ $customer->status === 'active' ? 'active' : 'inactive' }}"
                                onclick="confirmChange('{{ $customer->customerID }}', '{{ addslashes($customer->customerName) }}', '{{ $customer->status }}')">
                                {{ $customer->status === 'active' ? 'Archive' : 'Reactivate' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">No members found matching your criteria.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <form id="changeForm" method="POST" action="/admin/manage-membership/change-status" style="display:none;">
        @csrf
        <input type="hidden" name="customerID" id="changeCustomerID">
    </form>

    <script>
        function confirmChange(id, name, currentStatus) {
            const action = currentStatus === 'active' ? 'archive' : 'reactivate';
            if (confirm(`Are you sure you want to ${action} "${name}"?`)) {
                document.getElementById('changeCustomerID').value = id;
                document.getElementById('changeForm').submit();
            }
        }
    </script>
@endsection
