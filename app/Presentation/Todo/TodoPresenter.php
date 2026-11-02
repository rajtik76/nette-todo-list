<?php

declare(strict_types=1);

namespace App\Presentation\Todo;

use App\Model\Todo\TodoFacade;
use Nette;
use Nette\Application\UI\Form;


final class TodoPresenter extends Nette\Application\UI\Presenter
{
	public function __construct(
		private TodoFacade $todoFacade,
	) {
		parent::__construct();
	}


	public function renderDefault(): void
	{
		$this->template->todos = $this->todoFacade->findAll();
	}


	protected function createComponentTodoForm(): Form
	{
		$form = new Form;
		$form->addText('text', 'New Todo')
			->setRequired('Please enter a todo text')
			->setHtmlAttribute('placeholder', 'What needs to be done?')
			->setHtmlAttribute('class', 'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500');

		$form->addSubmit('add', 'Add Todo')
			->setHtmlAttribute('class', 'px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500');

		$form->onSuccess[] = $this->todoFormSucceeded(...);

		return $form;
	}


	private function todoFormSucceeded(Form $form, \stdClass $values): void
	{
		$this->todoFacade->createTodo($values->text);
		$form->reset();
		$this->redrawOrRedirect();
	}


	public function handleToggleTodo(int $id): void
	{
		$this->todoFacade->toggleTodo($id);
		$this->redrawOrRedirect();
	}


	public function handleDeleteTodo(int $id): void
	{
		$this->todoFacade->deleteTodo($id);
		$this->redrawOrRedirect();
	}


	private function redrawOrRedirect(): void
	{
		if ($this->isAjax()) {
			$this->redrawControl('todoList');
		} else {
			$this->redirect('this');
		}
	}
}
