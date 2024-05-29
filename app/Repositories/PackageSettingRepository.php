<?php

namespace App\Repositories;

use App\Models\PackageSetting;
use Repository\Baserepository\BaseRepository;
use Image;

class PackageSettingRepository extends BaseRepository
{
    private PackageSetting $packageSetting;

    public function __construct(PackageSetting $packageSetting)
    {
        $this->packageSetting = $packageSetting;
    }

    function model(): PackageSetting
    {
        return $this->packageSetting;
    }

    public function updatePackageSetting($id, $data){
        $packageSetting = $this->packageSetting->findOrfail($id);
        $packageSetting->package_id = $data['package_id'];
        $packageSetting->from_destination = $data['from_destination'];
        $packageSetting->from_dest_place_id = $data['from_dest_place_id'];
        $packageSetting->to_destination = $data['to_destination'];
        $packageSetting->to_dest_place_id = $data['to_dest_place_id'];
        $packageSetting->status = $data['status'];
        $packageSetting->trip_type = $data['trip_type'];
        $packageSetting->vehicle_type = $data['vehicle_type'];
        $packageSetting->starting_price = $data['starting_price'];
        $packageSetting->updated_by = $data['updated_by'];
        if ($data['image']){
            $destinationPath = public_path('images/');
            if($packageSetting->image != ''  && $packageSetting->image != null){
                $file_old = $destinationPath.$packageSetting->image;
                if( $file_old){
                    try {
                        unlink($file_old);
                    }catch(\Throwable $exception){

                    }
                }
            }
            $image = Image::make($data['image']);
            $imageName = time().'-'.$data['image']->getClientOriginalName();
            $image->save($destinationPath.$imageName);
            $packageSetting->image = $imageName;
        }
        $packageSetting->save();
        return $packageSetting;
    }
}
