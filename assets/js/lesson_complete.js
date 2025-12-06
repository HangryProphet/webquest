// Animate numbers on load
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const current = Math.floor(progress * (end - start) + start);
        element.textContent = current;
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Animate XP value
const xpElement = document.getElementById('xpValue');
const xpValue = parseInt(xpElement.textContent);
xpElement.textContent = '0';
setTimeout(() => {
    animateValue(xpElement, 0, xpValue, 1000);
}, 1000);

// Animate accuracy percentage
const accuracyElement = document.getElementById('accuracyValue');
const accuracyValue = parseInt(accuracyElement.textContent);
accuracyElement.textContent = '0%';
setTimeout(() => {
    animateValue(accuracyElement, 0, accuracyValue, 1000);
    setTimeout(() => {
        accuracyElement.textContent = accuracyValue + '%';
    }, 1000);
}, 1200);

// Proceed to next lesson
function proceedToNextLesson(event) {
    event.preventDefault();
    // You can customize this to redirect to the next lesson
    // For now, redirecting to dashboard
    // In a real implementation, you'd determine the next lesson URL
    window.location.href = 'dashboard.php';
}

