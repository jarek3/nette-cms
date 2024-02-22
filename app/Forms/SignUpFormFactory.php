<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model;
use Nette;
use Nette\Application\UI\Form;

/**
 * Továrna na registrační formulář.
 * @package App\Forms
 */

final class SignUpFormFactory
{
	use Nette\SmartObject;

    private const PASSWORD_MIN_LENGTH = 8;
	private FormFactory $factory;

	private Model\UserManager $userManager;


	public function __construct(FormFactory $factory, Model\UserManager $userManager)
	{
		$this->factory = $factory;
		$this->userManager = $userManager;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->addText('username', 'Pick a username:')
			->setRequired('Please pick a username.');

		$form->addEmail('email', 'Your e-mail:')
			->setRequired('Please enter your e-mail.');

		$form->addPassword('password', 'Create a password:');
        $form->addPassword('password_repeat', 'Heslo znovu')->setOmitted()->setRequired(false)
           ->addRule(Form::EQUAL, 'Hesla nesouhlasí.', $form['password']);

        $form->addText('y', 'Zadejte aktuální rok (antispam)')->setOmitted()->setRequired()
            ->addRule(Form::EQUAL, 'Chybně vyplněný antispam.', date("Y"));

		$form->addSubmit('send', 'Sign up');

		$form->onSuccess[] = function (Form $form, \stdClass $data) use ($onSuccess): void {
			try {
				$this->userManager->add($data->username, $data->email, $data->password);
			} catch (Model\DuplicateNameException $e) {
				$form['username']->addError('Username is already taken.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
