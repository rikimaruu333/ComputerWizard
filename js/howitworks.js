document.addEventListener("DOMContentLoaded", function() {
    const freelancerBtn = document.querySelector('.freelancer-btn');
    const clientBtn = document.querySelector('.client-btn');
    const clientGuide = document.getElementById('client-guide');
    const freelancerGuide = document.getElementById('freelancer-guide');

    freelancerBtn.addEventListener('click', function() {
        clientGuide.style.display = 'none';
        freelancerGuide.style.display = 'block';
    });

    clientBtn.addEventListener('click', function() {
        window.location.reload();
    });
});


window.addEventListener('load', function() {
    // Set up the intersection observer
    var observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('active'); // Add animation class
            } else {
                entry.target.classList.remove('active'); // Remove animation class when it's not in view
            }
        });
    }, { threshold: 0.1 }); // Trigger when 10% of the element is visible

    // Observe both steps containers
    var stepsContainers = document.querySelectorAll('.stepscontainer');
    stepsContainers.forEach(function(container) {
        observer.observe(container);
    });
});
