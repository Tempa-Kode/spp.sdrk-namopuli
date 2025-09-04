/* FAQ Accordion Enhanced JavaScript */
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced accordion behavior
    const accordionItems = document.querySelectorAll('.faq-accordion .card-header a');

    accordionItems.forEach(function(item) {
        item.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            ripple.style.left = (e.clientX - e.target.getBoundingClientRect().left) + 'px';
            ripple.style.top = (e.clientY - e.target.getBoundingClientRect().top) + 'px';

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Smooth scroll to opened accordion
    $('.faq-accordion .collapse').on('shown.bs.collapse', function() {
        const cardOffset = $(this).prev('.card-header').offset().top - 100;
        $('html, body').animate({
            scrollTop: cardOffset
        }, 500);
    });
});

// Add to existing custom.js or create ripple effect CSS
const rippleCSS = `
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
    width: 20px;
    height: 20px;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}
`;

// Inject ripple CSS
const style = document.createElement('style');
style.textContent = rippleCSS;
document.head.appendChild(style);
