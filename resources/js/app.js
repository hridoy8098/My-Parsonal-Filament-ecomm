import './bootstrap';
import 'preline'

document.addEventListener('livewire:navigated', () => { 
    // Call the autoInit method
    window.HSStaticMethods.autoInit();
});
