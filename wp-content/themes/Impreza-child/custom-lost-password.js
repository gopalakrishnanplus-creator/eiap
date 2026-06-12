document.addEventListener('DOMContentLoaded', function() {
    var originalText = 'Some text before the lost password form';
    var modifiedText = 'Your modified text before the lost password form';
    var targetElement = document.querySelector('.lost_password_before_form'); // Adjust the selector based on the actual class or ID of the element

    if (targetElement && targetElement.textContent.trim() === originalText) {
        targetElement.textContent = modifiedText;
    }
});
