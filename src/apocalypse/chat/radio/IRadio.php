<?php

namespace apocalypse\chat\radio;

interface IRadio {

    public function getChannel(): int;

    public function getPower(): float;

    public function getDistance(): int;

    public function getSerialNumber(): string;
}