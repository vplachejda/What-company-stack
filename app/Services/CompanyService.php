<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Company;
use App\Traits\companyPreviewTrait;
use Cloudinary\Api\Upload\UploadApi;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CompanyService
{
    use companyPreviewTrait;

    public function getAllCompanies(): array
    {
        $companies = Company::all();

        return compact('companies');
    }

    public function storeCompanyData(Request $request, Company $company)
    {
        try {
            $companyData = $request->all();
            if ($request->has('logo')) {
                $logoUrl = Cloudinary::upload(
                    $request->file('logo')->getRealPath(),
                    [
                        'folder' => 'wcsLogos',
                        'public_id' => $request->name,
                    ]
                )->getSecurePath();
                $companyData = $request->except('logo');
                $companyData['logo'] = $logoUrl;
            }
            return Company::create($companyData);
        } catch (\Exception $e) {

            return $e->getMessage(); // ??
        }
    }

    public function getCompanies(Company $company, $term = null)
    {
        if(!$term){
            return $company->FetchAllClientDetails();
        }
        $term = strtolower($term);
        $companies = $company->FetchAllClientDetails()
            ->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', "%{$term}%")
                    ->orWhereHas('plangs', function ($query) use ($term) {
                        $query->where('name', 'LIKE', "%{$term}%");
                    })
                    ->orWhereHas('frameworks', function ($query) use ($term) {
                        $query->where('name', 'LIKE', "%{$term}%");
                    })
                    ->orWhereHas('feFrameworks', function ($query) use ($term) {
                        $query->where('name', 'LIKE', "%{$term}%");
                    })
                    ->orWhereHas('mobilePlangs', function ($query) use ($term) {
                        $query->where('name', 'LIKE', "%{$term}%");
                    });
        });
        return $companies;
    }

    public function showCompany(int $id): Company
    {
        $company = $this->companyWithTechData()->find($id);
        return $company;
    }
}
