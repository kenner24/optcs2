<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use App\Services\StaffService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class CompanyStaffController extends Controller
{
    public function __construct(
        private StaffService $staffService,
        private CompanyService $companyService
    ) {
    }

    public function view()
    {
        $companyId = Crypt::decrypt(request()->get('id'));
        $details = $this->companyService->getCompanyDetails($companyId);
        return view('superAdmin.company.staff.staff-list', ['companyDetails' => $details]);
    }

    public function staffList()
    {

        $companyId = Crypt::decrypt(request()->get('id'));
        $data = $this->staffService->fetchStaffList($companyId);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $encryptedId = Crypt::encrypt($row->id);
                $deleteAccBtn = '<button onclick="deleteStaffAccount(\'' . $encryptedId . '\')" type="button" class="btn btn-outline-dark"><i class="fas fa-trash"></i></button>&nbsp;';
                $viewBtn = '<button type="button" class="btn btn-outline-dark" data-staffid="' . $encryptedId . '" data-toggle="modal" data-target="#viewStaffDetails">
                    <i class="fas fa-info"></i>
                </button>&nbsp;';

                return '<div class="btn-group" role="group">' . $deleteAccBtn . $viewBtn . '</div>';
            })
            ->editColumn('status', function ($row) {
                $encryptedId = Crypt::encrypt($row->id);
                $activateDeactivateBtn = $row->status === UserStatusEnum::ACTIVE ?
                '<button onclick="activateDeactivateStaffAccount(\'' . $encryptedId . '\', false)" type="button" class="btn btn-outline-success"><i class="fas fa-toggle-on"></i></button>':
                    '<button onclick="activateDeactivateStaffAccount(\'' . $encryptedId . '\', true)" type="button" class="btn btn-outline-danger"><i class="fas fa-toggle-off"></i></button>';
                return $activateDeactivateBtn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * handle the staff status update request
     */
    public function activateDeactivateStaffAccount(Request $request)
    {
        try {
            $userId = Crypt::decrypt($request->get('id'));
            $status = $request->get('status') ? UserStatusEnum::ACTIVE : UserStatusEnum::IN_ACTIVE;
            $this->staffService->updateStaffStatus($userId, $status);
            return $this->sendResponse('Status updated successfully', []);
        } catch (\Exception $th) {
            return $this->sendError('Internal server error', HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteAccount(Request $request)
    {
        try {
            $userId = Crypt::decrypt($request->get('id'));
            $this->staffService->delete($userId);
            return $this->sendResponse('Account Deleted Successfully', []);
        } catch (\Exception $th) {
            return $this->sendError('Internal Server Error', HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function staffDetailsView()
    {
        try {
            $staffId = Crypt::decrypt(request()->get('id'));
            $staffDetails = $this->staffService->getStaffDetails($staffId);
            $renderedView = view('superAdmin.company.staff.view', ['staff' => $staffDetails])->render();
            return $this->sendResponse('Success', ['view' => $renderedView]);
        } catch (\Exception $th) {
            return $this->sendError('Internal server error', HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
