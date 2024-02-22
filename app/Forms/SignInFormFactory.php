<?php

declare(strict_types=1);

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


final class SignInFormFactory
{
	use Nette\SmartObject;
    /** @var FormFactory Továrna na formuláře. */
    private FormFactory $factory;

    /** @var User Uživatel. */
    private User $user;

    /**
     * Konstruktor s injektovanou továrnou na formuláře a uživatelem.
     * @param FormFactory $factory automaticky injektovaná továrna na formuláře
     * @param User        $user    automaticky injektovaný uživatel
     */
	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->addText('username', 'Username:')
			->setRequired('Please enter your username.');

		$form->addPassword('password', 'Password:')
			->setRequired('Please enter your password.');

		$form->addCheckbox('remember', 'Keep me signed in');

		$form->addSubmit('send', 'Sign in');

		$form->onSuccess[] = function (Form $form, \stdClass $data) use ($onSuccess): void {
			try {
				$this->user->setExpiration($data->remember ? '14 days' : '20 minutes');
				$this->user->login($data->username, $data->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('The username or password you entered is incorrect.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
