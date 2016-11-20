# formGenerator

## Usage

`$form = $this->iForm->create();`

`$formGenerator = new FormGenerator();`

`$formGenerator->generate($form,$structure);`

`$form->addSubmit("submt","Přidat");`

`$form->onSuccess[] = array($this,"onAdd");`

`return $form;`
