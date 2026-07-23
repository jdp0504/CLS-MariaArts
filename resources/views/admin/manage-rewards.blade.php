@extends('layouts.admin')

@section('title', 'Manage Rewards — Maria Art\'s')

@section('showBack', 'true')

@section('styles')
        .page-header { margin-bottom: 1.75rem; }
        .page-header h1 { font-size: 1.65rem; font-weight: 800; letter-spacing: -0.03em; margin-bottom: 0.3rem; }
        .page-header p { color: var(--text-secondary); font-size: 0.92rem; }

        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
        .stat-box { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem 1.5rem; }
        .stat-box .label { font-size: 0.75rem; color: var(--text-tertiary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-box .value { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; }
        .stat-box .value.active { color: var(--accent-green); }

        .overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.45); backdrop-filter: blur(4px); z-index: 100; display: flex; align-items: flex-start; justify-content: center; overflow-y: auto; padding: 2rem 1rem; }
        body.modal-open { overflow: hidden; }
        .modal { background: var(--surface); border-radius: var(--radius-lg); padding: 2rem 2.25rem; width: 100%; max-width: 560px; margin: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.15); position: relative; animation: modalIn .2s ease-out; }
        @keyframes modalIn { from { opacity:0; transform:translateY(16px) scale(0.97); } to { opacity:1; transform:translateY(0) scale(1); } }
        .modal h3 { font-size: 1.15rem; font-weight: 700; margin-bottom: 0.2rem; }
        .modal .form-sub { font-size: 0.82rem; color: var(--text-secondary); margin-bottom: 1.25rem; }
        .modal-close { position: absolute; top: 1.25rem; right: 1.25rem; background: none; border: none; font-size: 1.3rem; color: var(--text-tertiary); cursor: pointer; padding: 4px; line-height: 1; border-radius: 4px; transition: color .2s, background .2s; }
        .modal-close:hover { color: var(--text-primary); background: var(--surface-alt); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
        .form-row.three { grid-template-columns: 1fr 1fr 1fr; }
        .field { display: flex; flex-direction: column; gap: 0.25rem; }
        .field.full { grid-column: 1 / -1; }
        .field label { font-size: 0.78rem; font-weight: 600; color: var(--text-secondary); }
        .field input, .field select, .field textarea {
            padding: 0.6rem 0.85rem; font-size: 0.88rem; font-family: inherit;
            border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            background: var(--surface-alt); outline: none; transition: border-color .2s;
        }
        .field input:focus, .field select:focus, .field textarea:focus { border-color: var(--brand); }
        .field textarea { resize: vertical; min-height: 60px; }
        .field .field-error { font-size: 0.75rem; color: var(--accent-red); font-weight: 500; }
        .field .field-hint { font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.15rem; }
        .btn { padding: 0.6rem 1.25rem; border: none; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.85rem; font-family: inherit; cursor: pointer; transition: background .2s; }
        .btn-primary { background: var(--brand); color: #fff; }
        .btn-primary:hover { background: var(--brand-light); }
        .btn-secondary { background: transparent; color: var(--text-secondary); border: 1px solid var(--border); }
        .btn-secondary:hover { background: var(--surface-alt); }
        .btn-group { display: flex; gap: 0.5rem; margin-top: 0.5rem; }

        .table-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--surface-alt); }
        th { text-align: left; padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 700; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 1px solid var(--border); }
        td { padding: 0.75rem 1rem; font-size: 0.85rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--surface-alt); }
        .badge { display: inline-flex; padding: 0.2rem 0.65rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; }
        .badge-active { background: var(--accent-green-bg); color: #065f46; }
        .badge-inactive { background: var(--accent-red-bg); color: #991b1b; }
        .action-btn { padding: 0.35rem 0.7rem; border: none; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600; font-family: inherit; cursor: pointer; transition: all .2s; }
        .action-btn.edit { background: var(--brand-subtle); color: var(--brand); }
        .action-btn.edit:hover { background: var(--brand); color: #fff; }
        .action-btn.archive { background: var(--accent-orange-bg); color: var(--accent-orange); }
        .action-btn.archive:hover { background: var(--accent-orange); color: #fff; }
        .action-btn.reactivate { background: #f1f5f9; color: var(--accent-green); }
        .action-btn.reactivate:hover { background: var(--accent-green); color: #fff; }
        .empty { text-align: center; padding: 2rem; color: var(--text-tertiary); font-size: 0.85rem; }
        .desc-cell { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-tertiary); font-size: 0.82rem; }
        .hidden { display: none !important; }
        .add-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }

        @media (max-width:860px) {
            .stats-row { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .form-row.three { grid-template-columns: 1fr; }
        }
@endsection

@section('content')
    <div class="page-header">
        <h1>Manage Rewards</h1>
        <p>Add, update, or archive rewards in the loyalty system.</p>
    </div>

    <div class="stats-row">
        <div class="stat-box">
            <span class="label">Total Rewards</span>
            <span class="value">{{ $totalActive + $totalInactive }}</span>
        </div>
        <div class="stat-box">
            <span class="label">Active</span>
            <span class="value active">{{ $totalActive }}</span>
        </div>
        <div class="stat-box">
            <span class="label">Inactive</span>
            <span class="value">{{ $totalInactive }}</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="add-bar">
        <span></span>
        <button type="button" class="btn btn-primary" onclick="openAddModal()">+ Add New Reward</button>
    </div>

    <div class="overlay hidden" id="rewardOverlay" onclick="if(event.target===this)hideForm()">
        <div class="modal">
            <button type="button" class="modal-close" onclick="hideForm()">&times;</button>
            <h3 id="formTitle">{{ $editReward ? 'Edit Reward' : 'Add New Reward' }}</h3>
            <p class="form-sub" id="formSub">{{ $editReward ? 'Update the reward details below.' : 'Fill in the details to create a new reward.' }}</p>

            <form method="POST" action="{{ $editReward ? '/admin/manage-rewards/update' : '/admin/manage-rewards/add' }}" id="rewardFormEl">
                @csrf
                <input type="hidden" name="rewardID" id="rewardIDInput" value="{{ $editReward->rewardID ?? '' }}">

                <div class="form-row">
                    <div class="field full">
                        <label>Reward Name</label>
                        <input type="text" name="rewardName" id="rewardName" value="{{ old('rewardName', $editReward->rewardName ?? '') }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field full">
                        <label>Description</label>
                        <textarea name="description" id="rewardDesc">{{ old('description', $editReward->description ?? '') }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>Points Required</label>
                        <input type="number" name="pointRequired" id="pointRequired" min="1" value="{{ old('pointRequired', $editReward->pointRequired ?? '') }}" required>
                    </div>
                    <div class="field">
                        <label id="stockLabel">Stock Quantity</label>
                        <p class="field-hint hidden" id="stockHint"></p>
                        <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $editReward->stock ?? '') }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field full">
                        <label>Status</label>
                        <select name="status" id="rewardStatus">
                            <option value="active" {{ old('status', $editReward->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $editReward->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" id="formSubmitBtn">{{ $editReward ? 'Update Reward' : 'Save Reward' }}</button>
                    <button type="button" class="btn btn-secondary" onclick="hideForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Points</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rewards as $reward)
                    <tr>
                        <td style="font-weight:600;font-size:0.8rem;">{{ $reward->rewardID }}</td>
                        <td>{{ $reward->rewardName }}</td>
                        <td class="desc-cell">{{ $reward->description ?? '—' }}</td>
                        <td>{{ number_format($reward->pointRequired) }}</td>
                        <td>{{ $reward->stock }}</td>
                        <td>
                            <span class="badge {{ $reward->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                {{ $reward->status ?? 'unknown' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:4px;flex-wrap:wrap;">
                                <button type="button" class="action-btn edit" onclick="editReward('{{ $reward->rewardID }}', '{{ addslashes($reward->rewardName) }}', '{{ addslashes($reward->description ?? '') }}', {{ $reward->pointRequired }}, {{ $reward->stock }}, '{{ $reward->status }}')">Edit</button>
                                <form method="POST" action="/admin/manage-rewards/archive" style="display:inline;" onsubmit="return confirm('Are you sure you want to {{ $reward->status === 'active' ? 'archive' : 'reactivate' }} "{{ $reward->rewardName }}"?');">
                                    @csrf
                                    <input type="hidden" name="rewardID" value="{{ $reward->rewardID }}">
                                    <button type="submit" class="action-btn {{ $reward->status === 'active' ? 'archive' : 'reactivate' }}">
                                        {{ $reward->status === 'active' ? 'Archive' : 'Reactivate' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">No rewards found. Add one using the form above.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function openAddModal() {
            hideForm();
            document.getElementById('rewardOverlay').classList.remove('hidden');
            document.body.classList.add('modal-open');
            document.getElementById('formTitle').textContent = 'Add New Reward';
            document.getElementById('formSub').textContent = 'Fill in the details to create a new reward.';
            document.getElementById('formSubmitBtn').textContent = 'Save Reward';
            document.getElementById('rewardFormEl').action = '/admin/manage-rewards/add';
            document.getElementById('rewardIDInput').value = '';
            document.getElementById('rewardName').value = '';
            document.getElementById('rewardDesc').value = '';
            document.getElementById('pointRequired').value = '';
            document.getElementById('stock').value = '';
            document.getElementById('stock').min = 0;
            document.getElementById('stock').setAttribute('required', 'required');
            document.getElementById('stockLabel').textContent = 'Stock Quantity';
            document.getElementById('stockHint').classList.add('hidden');
            document.getElementById('rewardStatus').value = 'active';
        }

        function hideForm() {
            document.getElementById('rewardOverlay').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        function editReward(id, name, desc, points, stock, status) {
            hideForm();
            document.getElementById('rewardOverlay').classList.remove('hidden');
            document.body.classList.add('modal-open');
            document.getElementById('rewardFormEl').action = '/admin/manage-rewards/update';
            document.getElementById('rewardIDInput').value = id;
            document.getElementById('rewardName').value = name;
            document.getElementById('rewardDesc').value = desc;
            document.getElementById('pointRequired').value = points;
            document.getElementById('stock').value = '';
            document.getElementById('stock').min = 0;
            document.getElementById('stock').removeAttribute('required');
            document.getElementById('stockLabel').textContent = 'Add Stock';
            document.getElementById('stockHint').textContent = 'Current stock: ' + stock;
            document.getElementById('stockHint').classList.remove('hidden');
            document.getElementById('rewardStatus').value = status;
            document.getElementById('formTitle').textContent = 'Edit Reward';
            document.getElementById('formSub').textContent = 'Update the reward details below.';
            document.getElementById('formSubmitBtn').textContent = 'Update Reward';
        }

        @if ($editReward)
            window.onload = function() {
                document.getElementById('rewardOverlay').classList.remove('hidden');
                document.body.classList.add('modal-open');
            };
        @endif
    </script>
@endsection
