<?php declare(strict_types=1);

namespace StaticMapLite\Output;

interface OutputInterface
{
    public function sendHeader();
    public function sendImage();
}
