<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveAssignmentRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Leave;
use App\Models\LeaveAssignment;
use App\Models\RemainingLeave;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveAssignmentController extends Controller
{
    public function index()
    {
        $data['branch'] = Branch::orderBy('name', 'asc')->get();
        $data['department'] = Department::orderBy('name', 'asc')->get();
        $data['employee'] = User::orderBy('firstname', 'asc')->get();
        $data['leave'] = Leave::orderBy('name', 'asc')->get();
        return view('leave-assignment.index', $data);
    }

    public function store(LeaveAssignmentRequest $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->leave_repeater as $item) {
                LeaveAssignment::create([
                    'leave_id' => $item['leave'],
                    'employee_id' => $request->employee,
                    'year' => $item['year'],
                    'allowed_days' => $item['allowed_days']
                ]);
                RemainingLeave::updateOrCreate(
                    [
                        'leave_id' => $item['leave'],
                        'employee_id' => $request->employee,
                        'year' => $item['year']
                    ],
                    [
                        'remaining_allowed_days' => $item['allowed_days']
                    ]
                );
            }
            DB::commit();
            return back()->with('success', 'Leave has been assigned');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
