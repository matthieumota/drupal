<?php

namespace Drupal\fiofio;

use Drupal\Core\Datetime\DrupalDateTime;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Person
{
    public function present(string $name, string $birthday, bool $upper = false): string
    {
        $birthday = new DrupalDateTime($birthday);

        if ($birthday->hasErrors()) {
            // throw new HttpException(422);
            throw new NotFoundHttpException(); // Renvoie une 404
        }

        $age = $birthday->diff(new DrupalDateTime())->y;

        return ($upper ? strtoupper($name) : $name).' a '.$age.' ans.';
    }
}
