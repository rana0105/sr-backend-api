<?php

namespace App\Services\Price;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Repository\CarTypeWisePriceRepository;
use Image;
class CarTypeWisePriceService
{
    private CarTypeWisePriceRepository $carTypeWisePriceRepository;

    public function __construct(CarTypeWisePriceRepository $carTypeWisePriceRepository){
        $this->carTypeWisePriceRepository = $carTypeWisePriceRepository;
    }

    public function getAll(): Collection
    {
        return $this->carTypeWisePriceRepository->carTypeWisePriceWithImageUrl();
    }

    public function findById($id): ?Model
    {
        return $this->carTypeWisePriceRepository->findByID($id);
    }

    public function create($data){
        $image = Image::make($data['image']);
        $imageName = time().'-'.$data['image']->getClientOriginalName();
        $destinationPath = public_path('images/');
        $image->save($destinationPath.$imageName);
        $data['image'] = $imageName;
        $data['created_by'] = request()->user()->user_code;
        return $this->carTypeWisePriceRepository->create($data);
    }

    public function update($data){
        $data['updated_by'] = request()->user()->user_code;
        return $this->carTypeWisePriceRepository->updateCarTypeWisePrice($data['id'], $data);
    }
    public function kmWisePrice($data){
        return $this->carTypeWisePriceRepository->kmWisePrice($data);
    }

    public function tripPrices($data){
        return $this->carTypeWisePriceRepository->tripPrices($data);
    }
}
