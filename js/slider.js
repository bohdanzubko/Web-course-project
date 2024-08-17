document.addEventListener('DOMContentLoaded', () => {
	const slider = document.querySelector('.slider');
	const slides = document.querySelectorAll('.slide');
	const prevButton = document.querySelector('.prev');
	const nextButton = document.querySelector('.next');
	const dotsContainer = document.querySelector('.dots-container');
	
	let currentIndex = 0;
	
	function updateSlider() {
		slider.style.transform = `translateX(-${currentIndex * 100}%)`;
		updateDots();
	}
	
	function updateDots() {
		const dots = document.querySelectorAll('.dot');
		dots.forEach((dot, index) => {
			dot.classList.toggle('active', index === currentIndex);
		});
	}
	
	slides.forEach((_, index) => {
		const dot = document.createElement('div');
		dot.classList.add('dot');
		if (index === currentIndex) dot.classList.add('active');
		dot.addEventListener('click', () => {
			currentIndex = index;
			updateSlider();
		});
		dotsContainer.appendChild(dot);
	});
	
	prevButton.addEventListener('click', () => {
		currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides.length - 1;
		updateSlider();
	});
	
	nextButton.addEventListener('click', () => {
		currentIndex = (currentIndex < slides.length - 1) ? currentIndex + 1 : 0;
		updateSlider();
	});
	
	updateSlider();
});
