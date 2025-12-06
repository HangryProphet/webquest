// Lecture stages
const lectureStages = [
    {
        text: "Hello! Are you ready to learn HTML?"
    },
    {
        text: "HTML stands for <strong>HyperText Markup Language</strong>. It is the standard markup language for creating web pages and web applications."
    },
    {
        text: "Let's learn first the basics of HTML. HTML uses <strong>tags</strong> to structure content. Tags are written with angle brackets like <code>&lt;tag&gt;</code>."
    },
    {
        text: "Most HTML tags come in pairs: an opening tag and a closing tag. For example, <code>&lt;h1&gt;</code> opens a heading, and <code>&lt;/h1&gt;</code> closes it. The content goes between them!"
    },
    {
        text: "Great! Now you understand the basics. HTML is the foundation of every webpage. Ready to practice? Let's start coding!"
    }
];

let currentStage = 0;
let isTyping = false;
let typewriterTimeout = null;
let currentTextSegments = [];
let currentSegmentIndex = 0;

// Wiza image sequence
const wizaImages = [
    'wiza-teach.png',
    'wiza-thinking.png',
    'wiza-teach-happy.png',
    'wiza-pointing.png', // Using heart-eyes as substitute for star-eyes
    'wiza-teach.png'
];

// Initialize progress bar
function initProgressBar() {
    const progressFill = document.getElementById('progressFill');
    if (progressFill) {
        // Set progress based on current stage (0-4 stages, so 0-80% for lecture)
        const progress = (currentStage / 5) * 100;
        progressFill.style.width = progress + '%';
    }
}

// Typewriter effect function
function typeWriter(text, element, baseSpeed = 30, callback) {
    if (isTyping) return; // Prevent multiple typewriter effects
    
    isTyping = true;
    const cursor = document.getElementById('typewriterCursor');
    
    // Update button states
    updateButtonStates();
    
    // Show cursor
    if (cursor) {
        cursor.style.display = 'inline-block';
    }
    
    // Clear any existing timeout
    if (typewriterTimeout) {
        clearTimeout(typewriterTimeout);
    }
    
    // Calculate adaptive speed based on text length
    // Remove HTML tags to get actual text length
    const textOnly = text.replace(/<[^>]*>/g, '');
    const textLength = textOnly.length;
    
    // Faster speed for longer text
    // Short text (< 50 chars): 30ms per char
    // Medium text (50-100 chars): 20ms per char
    // Long text (> 100 chars): 15ms per char
    let speed = baseSpeed;
    if (textLength > 100) {
        speed = 15; // Fast for long text
    } else if (textLength > 50) {
        speed = 20; // Medium speed
    } else {
        speed = 30; // Normal speed for short text
    }
    
    // Parse text into segments (text and HTML tags)
    const segments = [];
    let i = 0;
    
    while (i < text.length) {
        if (text[i] === '<') {
            // Find the closing '>'
            let tagEnd = text.indexOf('>', i);
            if (tagEnd === -1) {
                // Malformed tag, treat as regular text
                segments.push({ type: 'text', content: text[i] });
                i++;
            } else {
                // Complete tag found
                const tag = text.substring(i, tagEnd + 1);
                segments.push({ type: 'tag', content: tag });
                i = tagEnd + 1;
            }
        } else {
            // Regular text character
            segments.push({ type: 'text', content: text[i] });
            i++;
        }
    }
    
    // Store segments globally for skip functionality
    currentTextSegments = segments;
    currentSegmentIndex = 0;
    
    let index = 0;
    let currentHTML = '';
    
    function typeNext() {
        if (index >= segments.length) {
            // Typing complete
            isTyping = false;
            currentSegmentIndex = segments.length;
            if (cursor) {
                cursor.style.display = 'none';
            }
            updateButtonStates();
            if (callback) {
                callback();
            }
            return;
        }
        
        const segment = segments[index];
        currentSegmentIndex = index;
        
        if (segment.type === 'tag') {
            // Insert HTML tag immediately (no typing animation for tags)
            currentHTML += segment.content;
            element.innerHTML = currentHTML;
            index++;
            typewriterTimeout = setTimeout(typeNext, speed * 2); // Slight pause after tags
        } else {
            // Type text character by character
            currentHTML += segment.content;
            element.innerHTML = currentHTML;
            index++;
            typewriterTimeout = setTimeout(typeNext, speed);
        }
    }
    
    // Clear element and start typing
    currentHTML = '';
    element.innerHTML = '';
    typeNext();
}

// Skip animation function (for internal use when navigating)
function skipAnimation() {
    if (!isTyping) return; // Nothing to skip if not typing
    
    // Clear the timeout
    if (typewriterTimeout) {
        clearTimeout(typewriterTimeout);
        typewriterTimeout = null;
    }
    
    // Complete the text immediately
    const typewriterText = document.getElementById('typewriterText');
    const cursor = document.getElementById('typewriterCursor');
    
    if (typewriterText && currentTextSegments.length > 0) {
        let fullHTML = '';
        for (let i = 0; i < currentTextSegments.length; i++) {
            fullHTML += currentTextSegments[i].content;
        }
        typewriterText.innerHTML = fullHTML;
    }
    
    // Hide cursor and mark as not typing
    if (cursor) {
        cursor.style.display = 'none';
    }
    
    isTyping = false;
    currentSegmentIndex = currentTextSegments.length;
    
    // Update button states
    updateButtonStates();
    
    // Update next button text if on last stage
    const nextBtn = document.getElementById('nextBtn');
    if (nextBtn && currentStage === lectureStages.length - 1) {
        nextBtn.textContent = 'START PRACTICE';
    }
}

// Skip lecture function - redirects to activity
function skipLecture() {
    window.location.href = 'adventure1.php';
}

// Previous stage function
function previousStage() {
    if (isTyping) {
        // If typing, skip animation first
        skipAnimation();
        return;
    }
    
    if (currentStage > 0) {
        currentStage--;
        updateWizaImage();
        updateLectureContent();
        updateProgressBar();
        updateButtonStates();
    }
}

// Next stage function
function nextStage() {
    if (isTyping) {
        // If typing, skip animation first
        skipAnimation();
        return;
    }
    
    if (currentStage < lectureStages.length - 1) {
        currentStage++;
        updateWizaImage();
        updateLectureContent();
        updateProgressBar();
        updateButtonStates();
    } else {
        // Last stage - redirect to activity or next page
        window.location.href = 'adventure1.php';
    }
}

// Update button states
function updateButtonStates() {
    const backBtn = document.getElementById('backBtn');
    const skipBtn = document.getElementById('skipBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    // Skip button: always enabled
    if (skipBtn) {
        skipBtn.disabled = false;
    }
    
    // Back button: disabled on first stage
    if (backBtn) {
        backBtn.disabled = (currentStage === 0);
    }
    
    // Next button: disabled while typing
    if (nextBtn) {
        nextBtn.disabled = isTyping;
    }
}

// Update lecture content with typewriter effect
function updateLectureContent() {
    const typewriterText = document.getElementById('typewriterText');
    const nextBtn = document.getElementById('nextBtn');
    
    if (typewriterText && currentStage < lectureStages.length) {
        const text = lectureStages[currentStage].text;
        // Speed will be calculated automatically based on text length
        typeWriter(text, typewriterText, 30, function() {
            // Update button text on last stage after typing completes
            if (nextBtn && currentStage === lectureStages.length - 1) {
                nextBtn.textContent = 'START PRACTICE';
            }
            updateButtonStates();
        });
    }
}

// Update progress bar
function updateProgressBar() {
    const progressFill = document.getElementById('progressFill');
    if (progressFill) {
        const progress = ((currentStage + 1) / lectureStages.length) * 100;
        progressFill.style.width = progress + '%';
    }
}

// Update Wiza image based on current stage
function updateWizaImage() {
    const wizaImage = document.getElementById('wizaImage');
    if (wizaImage && currentStage < wizaImages.length) {
        wizaImage.src = '../assets/img/wiza/' + wizaImages[currentStage];
    }
}

// Exit Modal Functions
function showExitModal() {
    const exitModal = document.getElementById('exitModal');
    if (exitModal) {
        exitModal.classList.add('active');
    }
}

function closeModal() {
    const exitModal = document.getElementById('exitModal');
    if (exitModal) {
        exitModal.classList.remove('active');
    }
}

function exitToDashboard() {
    window.location.href = 'dashboard.php';
}

// Setup exit modal handlers
function setupExitModal() {
    const exitModal = document.getElementById('exitModal');
    if (exitModal) {
        // Close modal when clicking outside
        exitModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initProgressBar();
    setupExitModal();
    updateButtonStates();
    updateWizaImage();
    // Start typing the first stage
    updateLectureContent();
});

