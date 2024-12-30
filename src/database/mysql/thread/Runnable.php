<?php

namespace bulutbulutsun\CloudEssentials\database\mysql\thread;

interface Runnable {
    
    public function bindTo(PoolAdapter $class);
    public function run(): void;

}