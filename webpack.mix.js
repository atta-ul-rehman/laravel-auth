import { js } from 'laravel-mix';

js('Modules/Auth/Resources/assets/js/app.js', 'public/js')
    .postCss('Modules/Auth/Resources/css/app.css', 'public/css');
