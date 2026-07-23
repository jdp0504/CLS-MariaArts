<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reward;

class UpdateRewardController extends Controller
{
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
            'stock'          => 'nullable|integer|min:0',
            'status'         => 'required|in:active,inactive',
        ], [
            'rewardName.required'    => 'Blank Fields',
            'pointRequired.required' => 'Blank Fields',
            'pointRequired.min'      => 'Invalid Input',
            'stock.min'              => 'Invalid Input',
            'status.required'        => 'Blank Fields',
        ]);

        $reward = Reward::findOrFail($request->rewardID);
        $reward->update([
            'rewardName'     => $request->rewardName,
            'description'    => $request->description,
            'pointRequired'  => $request->pointRequired,
            'stock'          => $reward->stock + ($request->stock ?? 0),
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
}
