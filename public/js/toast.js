
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.koru-toast').forEach(toast => {
        const close = () => {
            toast.classList.add('is-hiding');

            setTimeout(() => {
                toast.remove();
            }, 300);
        };

        const timer = setTimeout(close, 4000);

        toast.querySelector('.koru-toast-close')
            ?.addEventListener('click', () => {
                clearTimeout(timer);
                close();
            });
    });
});
