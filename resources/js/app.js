import './bootstrap';

// Thirteen views drive their tabs, dropdowns and inline editors with Alpine
// directives (x-data / x-show / x-model). Nothing was ever loading Alpine, so
// every one of those panels rendered at once and none of the controls worked.
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();
