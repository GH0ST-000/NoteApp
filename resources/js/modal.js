// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Open modal when trigger is clicked
    document.querySelectorAll('.modal-trigger').forEach(trigger => {
        trigger.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-id');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        });
    });

    // Close modal when close button is clicked
    document.querySelectorAll('.modal-close').forEach(closeButton => {
        closeButton.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-id');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    });

    // Close modal when clicking outside the modal content
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('fixed') && 
            event.target.classList.contains('inset-0') && 
            !event.target.classList.contains('hidden')) {
            event.target.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const visibleModal = document.querySelector('.fixed.inset-0:not(.hidden):not(.bg-opacity-75)');
            if (visibleModal) {
                visibleModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
    });
});
