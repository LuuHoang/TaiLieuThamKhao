<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\CreateExtendPackageRequest;
use App\Http\Requests\WebRequests\CreateServicePackageRequest;
use App\Http\Requests\WebRequests\UpdateExtendPackageRequest;
use App\Http\Requests\WebRequests\UpdateServicePackageRequest;
use App\Http\Resources\ExtendPackageDetailResource;
use App\Http\Resources\ListExtendPackagesResource;
use App\Http\Resources\ListServicePackagesResource;
use App\Http\Resources\ServicePackageDetailResource;
use App\Services\PackageService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    private $_packageService;

    public function __construct(PackageService $packageService)
    {
        $this->_packageService = $packageService;
    }

    public function getListServicePackages(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort']);
        $servicePackages = $this->_packageService->getListServicePackages($limit, $paramQuery);

        return Response::pagination(
            ListServicePackagesResource::collection($servicePackages),
            $servicePackages->total(),
            $servicePackages->currentPage(),
            $limit
        );
    }
    public function getAllListServicePackages()
    {
        $servicePackages = $this->_packageService->getAllListServicePackages();
        return Response::success([
            'service_packages' => ListServicePackagesResource::collection($servicePackages)
        ]);
    }

    public function getServicePackage(int $packageId)
    {
        $package = $this->_packageService->getServicePackage($packageId);
        return Response::success([
            'package_data'  =>  new ServicePackageDetailResource($package)
        ]);
    }

    public function createServicePackage(CreateServicePackageRequest $request)
    {
        $packageData = $request->only('title', 'description', 'max_user', 'max_user_data', 'price');
        $package = $this->_packageService->createServicePackage($packageData);
        return Response::success([
            'package_data'  =>  new ServicePackageDetailResource($package)
        ]);
    }

    public function updateServicePackage(UpdateServicePackageRequest $request, int $packageId)
    {
        $packageData = $request->only('title', 'description', 'max_user', 'max_user_data', 'price');
        $package = $this->_packageService->updateServicePackage($packageData, $packageId);
        return Response::success([
            'package_data'  =>  new ServicePackageDetailResource($package)
        ]);
    }

    public function deleteServicePackage(int $packageId)
    {
        $this->_packageService->deleteServicePackage($packageId);
        return Response::success();
    }

    public function getListExtendPackages(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort']);
        $extendPackages = $this->_packageService->getListExtendPackages($limit, $paramQuery);

        return Response::pagination(
            ListExtendPackagesResource::collection($extendPackages),
            $extendPackages->total(),
            $extendPackages->currentPage(),
            $limit
        );
    }

    public function getExtendPackage(int $extendId)
    {
        $package = $this->_packageService->getExtendPackage($extendId);
        return Response::success([
            'extend_data'  =>  new ExtendPackageDetailResource($package)
        ]);
    }

    public function createExtendPackage(CreateExtendPackageRequest $request)
    {
        $extendData = $request->only('title', 'description', 'extend_user', 'extend_data', 'price');
        $extend = $this->_packageService->createExtendPackage($extendData);
        return Response::success([
            'extend_data'  =>  new ExtendPackageDetailResource($extend)
        ]);
    }

    public function updateExtendPackage(UpdateExtendPackageRequest $request, int $extendId)
    {
        $extendData = $request->only('title', 'description', 'extend_user', 'extend_data', 'price');
        $extend = $this->_packageService->updateExtendPackage($extendData, $extendId);
        return Response::success([
            'extend_data'  =>  new ExtendPackageDetailResource($extend)
        ]);
    }

    public function deleteExtendPackage(int $extendId)
    {
        $this->_packageService->deleteExtendPackage($extendId);
        return Response::success();
    }
}
