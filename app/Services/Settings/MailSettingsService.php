<?php

namespace App\Services\Settings;

use App\Repositories\MailSettingsRepository;

class MailSettingsService
{
    private MailSettingsRepository $mailSettingsRepository;

    public function __construct(MailSettingsRepository $mailSettingsRepository){
        $this->mailSettingsRepository = $mailSettingsRepository;
    }

    public function getAll(){
        return $this->mailSettingsRepository->getAll();
    }

    public function store($data){
        return $this->mailSettingsRepository->create($data);
    }

    public function findById($id){
        return $this->mailSettingsRepository->findByID($id);
    }

    public function update($id, $data){
        return $this->mailSettingsRepository->updateByID($id, $data);
    }

    public function getMailTypes(){
        return $this->mailSettingsRepository->getMailTypes();
    }
}
