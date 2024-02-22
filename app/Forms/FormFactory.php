<?php
declare(strict_types=1);

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;


final class FormFactory
{
    use Nette\SmartObject;

    /**
     * Vytváří a vrací nový formulář s výchozím nastavením.
     * @return Form nový formulář s výchozím nastavením
     */
    public function create(): Form
    {
        $form = new Form;
        return $form;
    }
}