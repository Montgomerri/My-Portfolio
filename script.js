// JavaScript for Portfolio Website

// Mobile Menu Toggle
document.querySelector('.menu-toggle').addEventListener('click', function() {
    document.querySelector('.nav-links').classList.toggle('active');
});

// Smooth Scrolling for Anchor Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
        
        // Close mobile menu if open
        document.querySelector('.nav-links').classList.remove('active');
    });
});

// Testimonial Slider
let currentSlide = 0;
const slides = document.querySelectorAll('.testimonial-slide');
const dots = document.querySelectorAll('.dot');

function showSlide(n) {
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    currentSlide = (n + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
}

document.querySelector('.testimonial-next').addEventListener('click', () => {
    showSlide(currentSlide + 1);
});

document.querySelector('.testimonial-prev').addEventListener('click', () => {
    showSlide(currentSlide - 1);
});

dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        showSlide(index);
    });
});

// Initialize first slide
showSlide(0);

// Sticky Header on Scroll
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    header.classList.toggle('sticky', window.scrollY > 0);
});

// Add this to your existing CSS:
// header.sticky {
//     background-color: rgba(255, 255, 255, 0.95);
//     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
// }