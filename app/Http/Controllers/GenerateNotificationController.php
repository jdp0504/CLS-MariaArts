<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Customer;
use App\Models\Notification;
use App\Mail\NotificationMail;

class GenerateNotificationController extends Controller
{
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

        if ($request->has('delete_draft')) {
            Notification::where('notificationID', $request->delete_draft)
                ->where('adminID', session('user_id'))
                ->where('status', 'draft')
                ->delete();
            return redirect('/admin/generate-notification')->with('success', 'Draft deleted.');
        }

        $filterType = $request->input('filter_type', 'all');
        $filterMonth = $request->input('filter_month', date('m'));

        $allCustomers = Customer::where('status', 'active')
            ->orderBy('customerName')
            ->get();

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

        $attachmentResult = $this->handleAttachment($request);
        if (isset($attachmentResult['error'])) {
            return redirect('/admin/generate-notification')->with('error', $attachmentResult['error']);
        }
        $attachmentPath = $attachmentResult['path'];
        if (!$attachmentPath && $request->input('existing_attachment')) {
            $attachmentPath = $request->input('existing_attachment');
        }

        $adminID = session('user_id');

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
}
