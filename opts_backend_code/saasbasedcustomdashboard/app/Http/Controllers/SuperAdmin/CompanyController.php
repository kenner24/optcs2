<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\FileUploadPathEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\EditDetailsRequest;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    public function __construct(
        private CompanyService $companyService
    ) {
    }

    /**
     * handle the datatable request
     */
    public function companyList()
    {
        $data = $this->companyService->fetchCompanyList();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                $encryptedId = Crypt::encrypt($row->id);
                $activateDeactivateBtn = $row->status === UserStatusEnum::ACTIVE ?
                    '<button onclick="activateDeactivateAccount(\'' . $encryptedId . '\', false)" type="button" class="btn btn-outline-success"><i class="fas fa-toggle-on"></i></button>&nbsp;' :
                    '<button onclick="activateDeactivateAccount(\'' . $encryptedId . '\', true)" type="button" class="btn btn-outline-danger"><i class="fas fa-toggle-off"></i></button>&nbsp;';
                return $activateDeactivateBtn;
            })
            ->addColumn('action', function ($row) {
                $encryptedId = Crypt::encrypt($row->id);
                $staffUrl = url('/admin/staff-list?id=' . $encryptedId);
                $reportUrl = url('/admin/company-reports?id=' . $encryptedId);
                $editAccUrl = url('/admin/company-edit-account?id=' . $encryptedId);

                $viewBtn = '<button type="button" class="btn btn-outline-dark" data-companyid="' . $encryptedId . '" data-toggle="modal" data-target="#viewCompanyDetails">
                            <i class="fas fa-info"></i>
                </button>';
                $editBtn = "<a href='{$editAccUrl}' class='btn btn-outline-dark'><i class='fas fa-user-edit'></i></a>&nbsp;";
                $staffListBtn = "<a href='{$staffUrl}' class='btn btn-outline-dark'><i class='fas fa-list'></i></a>&nbsp;";
                $reportBtn = "<a href='{$reportUrl}' class='btn btn-outline-dark'><i class='fas fa-chart-line'></i></a>&nbsp;";
                $deleteAccBtn = '<button onclick="deleteCompanyAccount(\'' . $encryptedId . '\')" type="button" class="btn btn-outline-dark"><i class="fas fa-trash"></i></button>&nbsp;';

                return '<div class="btn-group" role="group">' . $staffListBtn . $reportBtn . $deleteAccBtn . $editBtn . $viewBtn . '</div>';
            })
            ->addColumn('verified', function ($row) {
                return $row->email_verified_at === null ?
                    "<span class='badge badge-danger'>Not Verified</span>" :
                    "<span class='badge badge-success'>Verified</span>";
            })
            ->rawColumns(['action', 'status', 'verified'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function showView()
    {
        return view('superAdmin.company.list');
    }

    /**
     * Activate or deactivate the company account
     */
    public function activateDeactivateCompanyAccount(Request $request)
    {
        try {
            $userId = Crypt::decrypt($request->get('id'));
            $status = $request->get('status') ? UserStatusEnum::ACTIVE : UserStatusEnum::IN_ACTIVE;
            $this->companyService->updateCompanyStatus($userId, $status);
            return $this->sendResponse('Status updated successfully', []);
        } catch (\Exception $th) {
            return $this->sendError('Internal server error', HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteAccount(Request $request)
    {
        try {
            $userId = Crypt::decrypt($request->get('id'));
            $this->companyService->delete($userId);
            return $this->sendResponse('success', 'Account Deleted Successfully');
        } catch (\Exception $th) {
            return $this->sendError('Internal Server Error', HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editAccountView(Request $request)
    {
        $companyId = Crypt::decrypt($request->get('id'));
        $companyDetails = $this->companyService->getCompanyDetails($companyId);
        return view('superAdmin.company.edit', ['company' => $companyDetails, 'id' => $request->get('id')]);
    }

    public function updateAccountDetails(EditDetailsRequest $request)
    {
        $payload = $request->validated();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path(FileUploadPathEnum::COMPANY_FILE_UPLOAD->value), $imageName);
        }
        $payload['image'] = FileUploadPathEnum::COMPANY_FILE_UPLOAD->value . '/' . $imageName;
        $this->companyService->updateCompanyDetails(Crypt::decrypt($request->get('id')), $payload);
        return redirect()->route('admin.company.view')->with('success', 'Company details updated successfully.');
    }

    public function companyDetailsView()
    {
        try {
            $companyId = Crypt::decrypt(request()->get('id'));
            $companyDetails = $this->companyService->getCompanyDetails($companyId);
            $renderedView = view('superAdmin.company.view', ['company' => $companyDetails])->render();
            return $this->sendResponse('Success', ['view' => $renderedView]);
        } catch (\Exception $th) {
            return $this->sendError('Internal server error', HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
