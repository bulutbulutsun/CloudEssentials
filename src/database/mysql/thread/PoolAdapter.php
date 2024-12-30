<?php
namespace bulutbulutsun\CloudEssentials\database\mysql\thread;

interface PoolAdapter {

    public function run(string $qeury) : void;
    
}