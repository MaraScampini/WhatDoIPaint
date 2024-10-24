<?php

namespace App\Service\Element;

interface ElementServiceInterface
{
    public function removeElement(int $elementId): void;
}