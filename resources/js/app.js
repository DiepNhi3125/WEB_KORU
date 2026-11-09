
import Alpine from 'alpinejs';
import notificationBell from './notificationBell';

window.Alpine = Alpine;

Alpine.data('koruBell', notificationBell);

Alpine.start();
