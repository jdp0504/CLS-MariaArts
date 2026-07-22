@extends('layouts.admin')

@section('title', 'Generate Notification — Maria Art\'s Loyalty System')

@section('showBack', 'true')

@section('styles')
        .page-title { font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.25rem; }
        .page-subtitle { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem; }
        .two-col { display: grid; grid-template-columns: 1.6fr 1fr; gap: 1.5rem; align-items: start; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.5rem 2rem; margin-bottom: 1.25rem; }
        .card-title { font-weight: 700; font-size: 1rem; margin-bottom: 1rem; }

        .flash-msg { padding: 0.75rem 1.25rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 500; margin-bottom: 1rem; }
        .flash-success { background: var(--accent-green-bg); color: #065f46; border: 1px solid #a7f3d0; }
        .flash-error { background: var(--danger-bg); color: #991b1b; border: 1px solid #fecaca; }

        .draft-list { display: flex; flex-direction: column; gap: 8px; }
        .draft-item { display: flex; align-items: center; justify-content: space-between; background: var(--surface-alt); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0.75rem 1rem; }
        .draft-item.active-edit { border-color: var(--brand); background: #eef2ff; }
        .draft-item .draft-meta { display: flex; flex-direction: column; gap: 2px; flex: 1; min-width: 0; }
        .draft-item .draft-subject { font-size: 0.85rem; font-weight: 600; color: var(--text-primary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .draft-item .draft-preview { font-size: 0.78rem; color: var(--text-secondary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .draft-item .draft-date { font-size: 0.7rem; color: var(--text-tertiary); }
        .draft-actions { display: flex; gap: 6px; margin-left: 10px; flex-shrink: 0; }
        .btn-sm { padding: 4px 10px; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; }
        .btn-load { background: #e0e7ff; color: #3730a3; }
        .btn-load:hover { background: #c7d2fe; }
        .btn-del { background: var(--danger-bg); color: #991b1b; }
        .btn-del:hover { background: #fecaca; }
        .edit-badge { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; padding: 8px 14px; border-radius: 6px; font-size: 0.82rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .edit-badge a { font-size: 0.78rem; color: #c2410c; }

        .field { display: flex; flex-direction: column; gap: 5px; margin-bottom: 1rem; }
        .field label { font-size: 0.78rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.03em; }
        .field input[type="text"], .field textarea {
            padding: 0.65rem 0.9rem; border: 1px solid var(--border); border-radius: var(--radius-sm);
            font-size: 0.88rem; font-family: inherit; background: var(--surface); color: var(--text-primary); transition: border 0.2s;
        }
        .field input:focus, .field textarea:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-subtle); }
        .field textarea { resize: vertical; min-height: 110px; line-height: 1.55; }

        .tag-toolbar { display: flex; gap: 6px; flex-wrap: wrap; padding: 8px; background: #f0f9ff; border-radius: 6px; border: 1px solid #bae6fd; align-items: center; margin-bottom: 0.5rem; }
        .tag-toolbar .tt-label { font-size: 0.72rem; color: #0369a1; margin-right: 4px; }
        .tag-btn { background: white; border: 1px solid #7dd3fc; color: #0284c7; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; cursor: pointer; font-weight: 600; font-family: inherit; }
        .tag-btn:hover { background: #0284c7; color: white; }
        .tt-spacer { flex-grow: 1; }
        .btn-clear { background: var(--danger-bg); border: 1px solid #fecaca; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; cursor: pointer; font-weight: 600; font-family: inherit; }
        .btn-clear:hover { background: #fecaca; }

        .attach-row { margin: 0.75rem 0 1rem; }
        .btn-attach {
            display: inline-flex; align-items: center; gap: 6px;
            background: transparent; border: 1px solid var(--border); padding: 7px 14px;
            border-radius: var(--radius-sm); font-size: 0.82rem; font-weight: 600;
            cursor: pointer; color: var(--text-primary); font-family: inherit; transition: all 0.2s;
        }
        .btn-attach:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-subtle); }
        .attach-name { font-size: 0.8rem; color: var(--accent-green); margin-left: 8px; font-weight: 600; }

        .action-row { display: flex; gap: 0.75rem; margin-top: 0.75rem; }
        .btn-draft {
            flex: 1; background: var(--surface); color: var(--text-primary); border: 1px solid var(--border);
            padding: 0.65rem 1rem; border-radius: var(--radius-sm); font-weight: 600;
            font-size: 0.85rem; cursor: pointer; font-family: inherit; transition: all 0.2s;
        }
        .btn-draft:hover { background: var(--surface-alt); border-color: var(--text-tertiary); }
        .btn-send {
            flex: 2; background: var(--accent-green); color: #fff; border: none;
            padding: 0.65rem 1rem; border-radius: var(--radius-sm); font-weight: 600;
            font-size: 0.85rem; cursor: pointer; font-family: inherit; transition: background 0.2s;
        }
        .btn-send:hover { background: #047857; }

        .filter-row { display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap; margin-bottom: 0.75rem; }
        .filter-row .field { margin-bottom: 0; }
        .filter-row select { padding: 0.5rem 0.7rem; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.82rem; font-family: inherit; background: var(--surface); color: var(--text-primary); min-width: 120px; }
        .filter-row select:focus { outline: none; border-color: var(--brand); }
        .btn-preview { background: var(--brand); color: #fff; border: none; padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.8rem; cursor: pointer; font-family: inherit; }
        .btn-preview:hover { background: var(--brand-light); }

        .recip-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px; }
        .recip-header h2 { font-size: 1rem; }
        .quick-btns { display: flex; gap: 5px; }
        .btn-q { background: none; border: 1px solid var(--border); border-radius: 4px; padding: 3px 8px; font-size: 0.72rem; cursor: pointer; font-weight: 600; font-family: inherit; color: var(--text-secondary); }
        .btn-q:hover { background: var(--surface-alt); border-color: var(--text-tertiary); }
        .recip-list { max-height: 420px; overflow-y: auto; border: 1px solid var(--border); border-radius: var(--radius-sm); }
        .recip-item { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-bottom: 1px solid var(--border); cursor: pointer; transition: background 0.15s; }
        .recip-item:last-child { border-bottom: none; }
        .recip-item:hover { background: var(--surface-alt); }
        .recip-item input[type="checkbox"] { flex-shrink: 0; }
        .recip-item .r-info { flex: 1; min-width: 0; }
        .recip-item .r-name { font-size: 0.82rem; font-weight: 600; }
        .recip-item .r-email { font-size: 0.72rem; color: var(--text-tertiary); }
        .recip-item .r-badge { font-size: 0.65rem; padding: 1px 6px; border-radius: 4px; font-weight: 700; flex-shrink: 0; }
        .r-bday { background: #fff7ed; color: #c2410c; }
        .recip-summary { background: var(--surface-alt); padding: 10px 16px; border-radius: var(--radius-sm); margin-top: 10px; font-size: 0.82rem; color: var(--text-secondary); }

        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal-box { background: white; width: 92%; max-width: 600px; padding: 28px; border-radius: var(--radius-lg); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative; max-height: 90vh; overflow-y: auto; }
        .modal-header { font-size: 1.2rem; font-weight: 700; margin-bottom: 4px; display: flex; justify-content: space-between; align-items: center; }
        .modal-close { cursor: pointer; font-size: 1.3rem; color: var(--text-tertiary); background: none; border: none; font-family: inherit; }
        .modal-close:hover { color: var(--text-primary); }
        .modal-count { font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 10px; }
        .preview-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; background: var(--surface-alt); padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); }
        .preview-nav .nav-btn { background: white; border: 1px solid var(--border); padding: 4px 10px; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 0.8rem; color: var(--text-secondary); font-family: inherit; }
        .preview-nav .nav-btn:hover:not(:disabled) { background: var(--surface-alt); color: var(--text-primary); }
        .preview-nav .nav-btn:disabled { opacity: 0.4; cursor: not-allowed; }
        .preview-nav .nav-index { font-size: 0.82rem; color: var(--text-secondary); }
        .preview-pane { background: var(--brand-subtle); border: 1px solid #bae6fd; border-radius: var(--radius-sm); padding: 16px; margin-bottom: 16px; }
        .preview-label { font-size: 0.7rem; color: var(--brand); font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 3px; }
        .preview-subject { font-size: 1rem; font-weight: 600; color: #0c4a6e; margin-bottom: 12px; }
        .preview-body { font-size: 0.9rem; color: #0c4a6e; line-height: 1.6; white-space: pre-wrap; font-family: inherit; }
        .preview-attach { margin-top: 12px; border-top: 1px solid #bae6fd; padding-top: 10px; font-size: 0.82rem; color: #0c4a6e; }
        .modal-footer { display: flex; gap: 10px; }
        .btn-cancel-m { flex: 1; background: var(--surface); border: 1px solid var(--border); padding: 11px; border-radius: var(--radius-sm); font-weight: 600; cursor: pointer; color: var(--text-primary); font-family: inherit; }
        .btn-cancel-m:hover { background: var(--surface-alt); }
        .btn-confirm-m { flex: 1; background: var(--accent-green); color: white; border: none; padding: 11px; border-radius: var(--radius-sm); font-weight: 600; cursor: pointer; font-family: inherit; }
        .btn-confirm-m:hover { background: #047857; }
        .btn-confirm-m:disabled { background: #86efac; cursor: wait; }

        .hidden { display: none; }
        @media (max-width: 768px) { .two-col { grid-template-columns: 1fr; } }
@endsection

@section('content')
    <h1 class="page-title">Generate Notification</h1>
    <p class="page-subtitle">Compose and send personalized notifications to loyalty members.</p>

    @if (session('success'))
        <div class="flash-msg flash-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="flash-msg flash-error">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="flash-msg flash-error">@foreach ($errors->all() as $err) {{ $err }}<br> @endforeach</div>
    @endif

    @if ($drafts->count())
    <div class="card">
        <div class="card-title">Saved Drafts ({{ $drafts->count() }})</div>
        <div class="draft-list">
            @foreach ($drafts as $draft)
            <div class="draft-item @if($loadDraft && $loadDraft->notificationID == $draft->notificationID) active-edit @endif">
                <div class="draft-meta">
                    <span class="draft-subject">{{ $draft->subject ?: '(No Subject)' }}</span>
                    <span class="draft-preview">{{ Str::limit($draft->messageContent, 50) }}</span>
                    <span class="draft-date">{{ $draft->creationDate }}</span>
                </div>
                <div class="draft-actions">
                    <a href="?load_draft={{ $draft->notificationID }}" class="btn-sm btn-load">Load</a>
                    <a href="?delete_draft={{ $draft->notificationID }}" class="btn-sm btn-del" onclick="return confirm('Delete this draft?')">X</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if ($loadDraft)
    <div class="edit-badge">
        <span>Editing Draft</span>
        <a href="/admin/generate-notification">Stop Editing (New)</a>
    </div>
    @endif

    <div>
        <form method="POST" action="/admin/generate-notification" id="filterForm">
            @csrf
            <div class="card">
                <div class="card-title">Recipient Filter</div>
                <div class="filter-row">
                    <div class="field">
                        <label>Filter Type</label>
                        <select name="filter_type" onchange="toggleMonth()" id="filterTypeSelect">
                            <option value="all" {{ $filterType === 'all' ? 'selected' : '' }}>All Members</option>
                            <option value="birthday" {{ $filterType === 'birthday' ? 'selected' : '' }}>Birthday Month</option>
                        </select>
                    </div>
                    <div class="field" id="monthField">
                        <label>Month</label>
                        <select name="filter_month">
                            @foreach (['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'] as $val => $label)
                            <option value="{{ $val }}" {{ $filterMonth === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-preview">Apply Filter</button>
                </div>
            </div>
        </form>
    </div>

    <form method="POST" action="/admin/generate-notification/send" id="messageForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="draft_id" value="{{ $loadDraft->notificationID ?? '' }}">
        <input type="hidden" name="filter_type" value="{{ $filterType }}">
        <input type="hidden" name="filter_month" value="{{ $filterMonth }}">
        <input type="hidden" name="action" value="" id="actionInput">
        <input type="hidden" name="existing_attachment" value="{{ $loadDraft->attachment ?? '' }}">

        <div class="two-col">

            <div class="card">
                <div class="card-title">Message Composition</div>

                <div class="field">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subjectInput" value="{{ old('subject', $loadDraft->subject ?? '') }}" placeholder="e.g. Happy Birthday {name}!" required>
                </div>

                <div class="field">
                    <label for="messageContent">Message</label>
                    <div class="tag-toolbar">
                        <span class="tt-label">Insert:</span>
                        <button type="button" class="tag-btn" onclick="insertTag('{name}')">Name</button>
                        <button type="button" class="tag-btn" onclick="insertTag('{firstName}')">First Name</button>
                        <button type="button" class="tag-btn" onclick="insertTag('{phone}')">Phone</button>
                        <button type="button" class="tag-btn" onclick="insertTag('{points}')">Points</button>
                        <button type="button" class="tag-btn" onclick="insertTag('{referralCode}')">Referral</button>
                        <button type="button" class="tag-btn" onclick="insertTag('{email}')">Email</button>
                        <span class="tt-spacer"></span>
                        <button type="button" class="btn-clear" onclick="clearForm()">Clear All</button>
                    </div>
                    <textarea name="messageContent" id="messageBox" rows="7" placeholder="Type your notification message...">{{ old('messageContent', $loadDraft->messageContent ?? '') }}</textarea>
                </div>

                <div class="attach-row">
                    <input type="file" name="attachment" id="fileInput" style="display:none" onchange="updateAttachLabel()">
                    <button type="button" class="btn-attach" onclick="document.getElementById('fileInput').click()">
                        Add Attachment
                    </button>
                    <span class="attach-name" id="attachLabel">
                        @if ($loadDraft && $loadDraft->attachment)
                            {{ basename($loadDraft->attachment) }}
                        @endif
                    </span>
                </div>

                <div class="action-row">
                    <button type="button" class="btn-draft" onclick="submitAction('draft')">
                        {{ $loadDraft ? 'Update Draft' : 'Save as Draft' }}
                    </button>
                    <button type="button" class="btn-send" id="sendBtn">Send to <span id="sendCount">0</span> Recipient(s)</button>
                </div>
            </div>

            <div class="card" style="padding: 1.25rem 1.25rem;">
                <div class="recip-header">
                    <h2>Recipients</h2>
                    <div class="quick-btns">
                        <button type="button" class="btn-q" id="selectAllBtn">All</button>
                        <button type="button" class="btn-q" id="selectBdayBtn">Birthday</button>
                        <button type="button" class="btn-q" id="deselectAllBtn">None</button>
                    </div>
                </div>

                <div class="recip-list" id="recipientList">
                    @forelse ($allCustomers as $c)
                    @php
                        $isBday = $c->birthDate && date('m-d', strtotime($c->birthDate)) === date('m-d');
                        $isPreSelected = $preSelectedIds->contains($c->customerID);
                    @endphp
                    <label class="recip-item">
                        <input type="checkbox" name="recipients[]" class="cust-cb"
                               value="{{ $c->customerID }}"
                               {{ $isPreSelected ? 'checked' : '' }}
                               data-name="{{ $c->customerName }}"
                               data-firstname="{{ explode(' ', $c->customerName)[0] }}"
                               data-phone="{{ $c->phoneNumber ?? 'N/A' }}"
                               data-points="{{ $c->currentPoints ?? 0 }}"
                               data-ref="{{ $c->referralCode ?? 'N/A' }}"
                               data-email="{{ $c->email ?? '' }}">
                        <div class="r-info">
                            <div class="r-name">{{ $c->customerName }} @if($isBday) 🎂 @endif</div>
                            <div class="r-email">{{ $c->email }}</div>
                        </div>
                        @if($isBday)
                        <span class="r-badge r-bday">BDAY</span>
                        @endif
                    </label>
                    @empty
                    <div style="padding: 1rem; color: var(--text-tertiary); font-size: 0.85rem;">No active customers found.</div>
                    @endforelse
                </div>

                <div class="recip-summary">
                    <strong id="selectedCount">0</strong> of <strong>{{ $allCustomers->count() }}</strong> selected
                </div>
            </div>

        </div>
    </form>

    <div class="modal-overlay" id="previewModal">
        <div class="modal-box">
            <div class="modal-header">
                Confirm Notification
                <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-count">Sending to <strong id="modalCount">0</strong> recipient(s)</div>

            <div class="preview-nav">
                <button type="button" class="nav-btn" id="prevPreviewBtn">&larr; Prev</button>
                <span class="nav-index"><strong id="previewName">-</strong> (<span id="previewIndex">1/1</span>)</span>
                <button type="button" class="nav-btn" id="nextPreviewBtn">Next &rarr;</button>
            </div>

            <div class="preview-pane">
                <div class="preview-label">Subject</div>
                <div class="preview-subject" id="previewSubject"></div>
                <div class="preview-label">Message</div>
                <div class="preview-body" id="previewBody"></div>
                <div class="preview-attach" id="previewAttach" style="display:none">
                    Attachment: <span id="previewAttachName"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel-m" onclick="closeModal()">Edit Message</button>
                <button type="button" class="btn-confirm-m" id="confirmSendBtn" onclick="confirmSend()">Confirm & Send</button>
            </div>
        </div>
    </div>

    <script>
        function toggleMonth() {
            var sel = document.getElementById('filterTypeSelect');
            var mf = document.getElementById('monthField');
            mf.style.display = sel.value === 'birthday' ? '' : 'none';
        }
        toggleMonth();

        function insertTag(tag) {
            var mb = document.getElementById('messageBox');
            var start = mb.selectionStart, end = mb.selectionEnd;
            mb.value = mb.value.substring(0, start) + tag + mb.value.substring(end);
            mb.focus(); mb.selectionStart = mb.selectionEnd = start + tag.length;
        }

        function clearForm() {
            if (!confirm('Clear subject, message, and attachment?')) return;
            document.getElementById('subjectInput').value = '';
            document.getElementById('messageBox').value = '';
            document.getElementById('fileInput').value = '';
            document.getElementById('attachLabel').textContent = '';
        }

        function updateAttachLabel() {
            var fi = document.getElementById('fileInput');
            var al = document.getElementById('attachLabel');
            al.textContent = fi.files.length > 0 ? fi.files[0].name : '';
        }

        function submitAction(action) {
            document.getElementById('actionInput').value = action;
            document.getElementById('messageForm').submit();
        }

        var checkboxes = document.querySelectorAll('.cust-cb');
        var selectedCountEl = document.getElementById('selectedCount');
        var sendCountEl = document.getElementById('sendCount');

        function updateCounts() {
            var checked = document.querySelectorAll('.cust-cb:checked').length;
            if (selectedCountEl) selectedCountEl.textContent = checked;
            if (sendCountEl) sendCountEl.textContent = checked;
        }
        checkboxes.forEach(function(cb) { cb.addEventListener('change', updateCounts); });
        document.getElementById('selectAllBtn').addEventListener('click', function() {
            checkboxes.forEach(function(cb) { cb.checked = true; });
            updateCounts();
        });
        document.getElementById('deselectAllBtn').addEventListener('click', function() {
            checkboxes.forEach(function(cb) { cb.checked = false; });
            updateCounts();
        });
        document.getElementById('selectBdayBtn').addEventListener('click', function() {
            checkboxes.forEach(function(cb) {
                var item = cb.closest('.recip-item');
                cb.checked = item && item.querySelector('.r-bday') !== null;
            });
            updateCounts();
        });
        updateCounts();

        var modal = document.getElementById('previewModal');
        var selectedData = [];
        var currentIndex = 0;

        document.getElementById('sendBtn').addEventListener('click', function() {
            var checked = document.querySelectorAll('.cust-cb:checked');
            if (checked.length === 0) { alert('Select at least one recipient.'); return; }
            var subj = document.getElementById('subjectInput').value.trim();
            var msg = document.getElementById('messageBox').value.trim();
            if (!subj) { alert('Subject is required.'); return; }
            if (!msg) { alert('Message is required.'); return; }

            selectedData = [];
            checked.forEach(function(cb) { selectedData.push(cb.dataset); });
            currentIndex = 0;
            document.getElementById('modalCount').textContent = selectedData.length;
            updatePreview();

            var fi = document.getElementById('fileInput');
            var ea = document.querySelector('input[name="existing_attachment"]').value;
            var pa = document.getElementById('previewAttach');
            var pan = document.getElementById('previewAttachName');
            if (fi.files.length > 0) { pa.style.display = 'block'; pan.textContent = fi.files[0].name; }
            else if (ea) { pa.style.display = 'block'; pan.textContent = ea.split(/[/\\]/).pop(); }
            else { pa.style.display = 'none'; }

            modal.style.display = 'flex';
        });

        function updatePreview() {
            var d = selectedData[currentIndex];
            if (!d) return;
            var subj = replaceTags(document.getElementById('subjectInput').value, d);
            var msg = replaceTags(document.getElementById('messageBox').value, d);
            document.getElementById('previewName').textContent = d.name;
            document.getElementById('previewIndex').textContent = (currentIndex + 1) + '/' + selectedData.length;
            document.getElementById('previewSubject').textContent = subj;
            document.getElementById('previewBody').textContent = msg;
            document.getElementById('prevPreviewBtn').disabled = currentIndex === 0;
            document.getElementById('nextPreviewBtn').disabled = currentIndex === selectedData.length - 1;
            document.getElementById('prevPreviewBtn').style.opacity = currentIndex === 0 ? 0.4 : 1;
            document.getElementById('nextPreviewBtn').style.opacity = currentIndex === selectedData.length - 1 ? 0.4 : 1;
        }

        function replaceTags(text, data) {
            return text
                .replace(/{name}/g, data.name)
                .replace(/{firstName}/g, data.firstName)
                .replace(/{phone}/g, data.phone)
                .replace(/{points}/g, data.points)
                .replace(/{referralCode}/g, data.ref)
                .replace(/{email}/g, data.email);
        }

        document.getElementById('prevPreviewBtn').onclick = function() {
            if (currentIndex > 0) { currentIndex--; updatePreview(); }
        };
        document.getElementById('nextPreviewBtn').onclick = function() {
            if (currentIndex < selectedData.length - 1) { currentIndex++; updatePreview(); }
        };

        function closeModal() { modal.style.display = 'none'; }

        function confirmSend() {
            var btn = document.getElementById('confirmSendBtn');
            btn.disabled = true; btn.textContent = 'Sending...';
            document.getElementById('actionInput').value = 'send';
            document.getElementById('messageForm').submit();
        }

        window.onclick = function(e) { if (e.target == modal) closeModal(); }
    </script>
@endsection
