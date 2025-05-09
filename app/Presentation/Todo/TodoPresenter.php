<?php

declare(strict_types=1);

namespace App\Presentation\Todo;

use App\Model\Todo\TodoFacade;
use Nette;
use Nette\Application\UI\Form;

/**
 * Presenter for handling Todo-related actions.
 * Manages the todo list display, form handling, and AJAX operations.
 */
final class TodoPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private TodoFacade $todoFacade,
    ) {
        parent::__construct();
    }

    /**
     * Renders the default view with the list of todos.
     * Handles AJAX redrawing of the todo list if needed.
     */
    public function renderDefault(): void
    {
        $this->template->todos = $this->todoFacade->findAll();

        if ($this->isAjax()) {
            $this->redrawControl('todoList');
        }
    }

    /**
     * Creates and configures the todo form component.
     */
    protected function createComponentTodoForm(): Form
    {
        $form = new Form;
        $form->addText('text', 'New Todo')
            ->setRequired('Please enter a todo text')
            ->setHtmlAttribute('placeholder', 'What needs to be done?')
            ->setHtmlAttribute('class', 'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500');

        $form->addSubmit('add', 'Add Todo')
            ->setHtmlAttribute('class', 'px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500');

        $form->onSuccess[] = [$this, 'todoFormSucceeded'];

        return $form;
    }

    /**
     * Handles the successful submission of the todo form.
     * Creates a new todo and updates the UI accordingly.
     */
    public function todoFormSucceeded(Form $form, \stdClass $values): void
    {
        $this->todoFacade->createTodo($values->text);

        if ($this->isAjax()) {
            $this->redrawControl('todoList');
            $form->reset();
        } else {
            $this->redirect('this');
        }
    }

    /**
     * Handles the toggle action for a todo item.
     * Toggles the completion status and updates the UI.
     */
    public function handleToggleTodo(int $id): void
    {
        $this->todoFacade->toggleTodo($id);

        if ($this->isAjax()) {
            $this->redrawControl('todoList');
        } else {
            $this->redirect('this');
        }
    }

    /**
     * Handles the delete action for a todo item.
     * Removes the todo from the database and updates the UI.
     */
    public function handleDeleteTodo(int $id): void
    {
        $this->todoFacade->deleteTodo($id);

        if ($this->isAjax()) {
            $this->redrawControl('todoList');
        } else {
            $this->redirect('this');
        }
    }
}
