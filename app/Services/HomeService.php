<?php

namespace App\Services;

use App\Repositories\HomeRepository;

class HomeService
{
    private $repo;

    public function __construct(HomeRepository $repo ){
            $this->repo = $repo;
    }

    public function getMenu(){
        return $this->repo->getMenu();
    }

    public function getTotalPenghargaan(){
        return $this->repo->getTotalPenghargaan();
    }

    public function getTotalInovasi(){
        return $this->repo->getTotalInovasi();
    }

    public function getVisi(){
        return $this->repo->getVisi();
    }

    public function getTotalPenghargaanReady(){
        return $this->repo->getTotalPenghargaanReady();
    }

}
