<?php

namespace App\Services\Package;

use App\Repositories\PackageSettingRepository;
use Image;

class PackageSettingService
{
    private PackageSettingRepository $packageSettingRepository;

    public function __construct(PackageSettingRepository $packageSettingRepository){
        $this->packageSettingRepository = $packageSettingRepository;
    }
    public function getPackages(){
        return $this->packageSettingRepository->getAll();
    }
    public function create($data){
        $image = Image::make($data['image']);
        $imageName = time().'-'.$data['image']->getClientOriginalName();
        $destinationPath = public_path('images/');
        $image->save($destinationPath.$imageName);
        $data['image'] = $imageName;
        $data['created_by'] = request()->user()->user_code;
        return $this->packageSettingRepository->create($data);
    }

    public function update($data){
        $data['updated_by'] = request()->user()->user_code;
        return $this->packageSettingRepository->updatePackageSetting($data['id'], $data);
    }

    public function findById($id){
        return $this->packageSettingRepository->findByID($id);
    }
}
