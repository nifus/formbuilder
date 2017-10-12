<?php

\Route::any('/formbuilder/{ext}/{action}', ['uses'=>'Nifus\FormBuilder\Field@Index','as'=>'fb.action']);
\Route::any('/formbuilder/example', 'Nifus\FormBuilder\Example@Index');
