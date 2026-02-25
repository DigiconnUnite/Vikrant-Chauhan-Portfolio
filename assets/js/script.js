/**
 * Custom JavaScript for Vikrant Portfolio
 * Contains hero slider and sticky header functionality
 */

(function () {
  "use strict";

  // Wait for DOM to be fully loaded
  document.addEventListener("DOMContentLoaded", function () {
    /**
     * ========================================
     * Sticky Header on Scroll
     * ========================================
     */
    const header = document.getElementById("header-sticky");

    if (header) {
      window.addEventListener("scroll", function () {
        if (window.pageYOffset > 100) {
          header.classList.add("header-sticky");
        } else {
          header.classList.remove("header-sticky");
        }
      });
    }

    /**
     * ========================================
     * Hero Slider Functionality
     * ========================================
     */
    const heroSlider = {
      slides: document.querySelectorAll(".hero-slide"),
      prevBtn: document.querySelector(".hero-slider-prev"),
      nextBtn: document.querySelector(".hero-slider-next"),
      currentIndex: 0,
      autoPlayInterval: null,
      autoPlayDelay: 5000, // 5 seconds

      init: function () {
        if (this.slides.length === 0) return;

        // Add click event listeners to navigation buttons
        if (this.prevBtn) {
          this.prevBtn.addEventListener("click", () => this.prevSlide());
        }

        if (this.nextBtn) {
          this.nextBtn.addEventListener("click", () => this.nextSlide());
        }

        // Start autoplay
        this.startAutoPlay();

        // Pause autoplay on hover
        const sliderSection = document.querySelector(".hero-slider-section");
        if (sliderSection) {
          sliderSection.addEventListener("mouseenter", () =>
            this.stopAutoPlay()
          );
          sliderSection.addEventListener("mouseleave", () =>
            this.startAutoPlay()
          );
        }

        // Add keyboard navigation
        document.addEventListener("keydown", (e) => {
          if (e.key === "ArrowLeft") {
            this.prevSlide();
          } else if (e.key === "ArrowRight") {
            this.nextSlide();
          }
        });

        // Add touch/swipe support for mobile
        this.addTouchSupport();
      },

      showSlide: function (index) {
        // Remove active class from all slides
        this.slides.forEach((slide) => {
          slide.classList.remove("active");
        });

        // Ensure index is within bounds
        if (index >= this.slides.length) {
          this.currentIndex = 0;
        } else if (index < 0) {
          this.currentIndex = this.slides.length - 1;
        } else {
          this.currentIndex = index;
        }

        // Add active class to current slide
        this.slides[this.currentIndex].classList.add("active");
      },

      nextSlide: function () {
        this.showSlide(this.currentIndex + 1);
        this.resetAutoPlay();
      },

      prevSlide: function () {
        this.showSlide(this.currentIndex - 1);
        this.resetAutoPlay();
      },

      startAutoPlay: function () {
        if (this.slides.length <= 1) return;

        this.autoPlayInterval = setInterval(() => {
          this.nextSlide();
        }, this.autoPlayDelay);
      },

      stopAutoPlay: function () {
        if (this.autoPlayInterval) {
          clearInterval(this.autoPlayInterval);
          this.autoPlayInterval = null;
        }
      },

      resetAutoPlay: function () {
        this.stopAutoPlay();
        this.startAutoPlay();
      },

      addTouchSupport: function () {
        const sliderSection = document.querySelector(".hero-slider-section");
        if (!sliderSection) return;

        let touchStartX = 0;
        let touchEndX = 0;
        const minSwipeDistance = 50; // Minimum distance for a swipe

        sliderSection.addEventListener(
          "touchstart",
          (e) => {
            touchStartX = e.changedTouches[0].screenX;
          },
          { passive: true }
        );

        sliderSection.addEventListener(
          "touchend",
          (e) => {
            touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe(touchStartX, touchEndX, minSwipeDistance);
          },
          { passive: true }
        );
      },

      handleSwipe: function (startX, endX, minDistance) {
        const difference = startX - endX;

        if (Math.abs(difference) > minDistance) {
          if (difference > 0) {
            // Swiped left - show next slide
            this.nextSlide();
          } else {
            // Swiped right - show previous slide
            this.prevSlide();
          }
        }
      },
    };

    // Initialize hero slider
    heroSlider.init();

    /**
     * ========================================
     * Accessibility Enhancements
     * ========================================
     */
    // Add focus trap for slider buttons
    const sliderButtons = document.querySelectorAll(
      ".hero-slider-prev, .hero-slider-next"
    );
    sliderButtons.forEach((button) => {
      button.addEventListener("focus", () => {
        heroSlider.stopAutoPlay();
      });

      button.addEventListener("blur", () => {
        heroSlider.startAutoPlay();
      });
    });

    /**
     * ========================================
     * Smooth Scroll for Navigation Links
     * ========================================
     */
    const navLinks = document.querySelectorAll('.tp-onepage-menu a[href^="#"]');
    navLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        const targetId = this.getAttribute("href");
        const targetElement = document.querySelector(targetId);

        if (targetElement) {
          const headerHeight = header ? header.offsetHeight : 0;
          const targetPosition = targetElement.offsetTop - headerHeight - 20;

          window.scrollTo({
            top: targetPosition,
            behavior: "smooth",
          });
        }
      });
    });

    /**
     * ========================================
     * Testimonial Slider - Show 2 Cards at a Time
     * ========================================
     */
    const testimonialSlider = {
      slides: document.querySelectorAll(
        ".tp-testimonial-2-active .swiper-slide"
      ),
      prevBtn: document.querySelector(".testimonial-arrow-prev"),
      nextBtn: document.querySelector(".testimonial-arrow-next"),
      currentIndex: 0,
      slidesToShow: 2, // Show 2 cards at a time on desktop

      init: function () {
        if (this.slides.length === 0) return;

        // Update slides to show based on screen size
        this.updateSlidesToShow();
        window.addEventListener("resize", () => this.updateSlidesToShow());

        // Initial display
        this.showSlides();

        // Add event listeners
        if (this.prevBtn) {
          this.prevBtn.addEventListener("click", () => this.prevSlide());
        }

        if (this.nextBtn) {
          this.nextBtn.addEventListener("click", () => this.nextSlide());
        }
      },

      updateSlidesToShow: function () {
        const width = window.innerWidth;
        if (width < 768) {
          this.slidesToShow = 1;
        } else if (width < 1200) {
          this.slidesToShow = 1;
        } else {
          this.slidesToShow = 2;
        }
        this.showSlides();
      },

      showSlides: function () {
        // Hide all slides
        this.slides.forEach((slide) => {
          slide.style.display = "none";
        });

        // Show current slides
        for (let i = 0; i < this.slidesToShow; i++) {
          const slideIndex = (this.currentIndex + i) % this.slides.length;
          this.slides[slideIndex].style.display = "block";
        }
      },

      nextSlide: function () {
        this.currentIndex = (this.currentIndex + 1) % this.slides.length;
        this.showSlides();
      },

      prevSlide: function () {
        this.currentIndex =
          (this.currentIndex - 1 + this.slides.length) % this.slides.length;
        this.showSlides();
      },
    };

    // Initialize testimonial slider
    testimonialSlider.init();

    /**
     * ========================================
     * Service Carousel - Show 4 Cards at a Time
     * ========================================
     */
    const serviceCarousel = {
      track: document.querySelector(".tp-service-carousel-track"),
      slides: document.querySelectorAll(".tp-service-carousel-slide"),
      prevBtn: document.querySelector(".service-arrow-prev"),
      nextBtn: document.querySelector(".service-arrow-next"),
      currentIndex: 0,
      slidesToShow: 4,
      slideWidth: 0,
      gap: 20,

      init: function () {
        if (!this.track || this.slides.length === 0) return;

        // Update slides to show based on screen size
        this.updateSlidesToShow();
        window.addEventListener("resize", () => {
          this.updateSlidesToShow();
          this.updatePosition(false);
        });

        // Calculate initial position
        this.updatePosition(false);

        // Add event listeners
        if (this.prevBtn) {
          this.prevBtn.addEventListener("click", () => this.prevSlide());
        }

        if (this.nextBtn) {
          this.nextBtn.addEventListener("click", () => this.nextSlide());
        }

        // Update button states
        this.updateButtons();
      },

      updateSlidesToShow: function () {
        const width = window.innerWidth;
        if (width < 768) {
          this.slidesToShow = 1;
          this.gap = 0;
        } else if (width < 992) {
          this.slidesToShow = 2;
          this.gap = 20;
        } else if (width < 1200) {
          this.slidesToShow = 3;
          this.gap = 20;
        } else {
          this.slidesToShow = 3; // Changed from 4 to 3 for laptop view
          this.gap = 20;
        }
      },

      updatePosition: function (animate = true) {
        if (!this.track) return;

        // Calculate slide width based on container and gaps
        const containerWidth = this.track.parentElement.offsetWidth;
        this.slideWidth =
          (containerWidth - this.gap * (this.slidesToShow - 1)) /
          this.slidesToShow;

        // Calculate translateX value - scroll one card at a time
        const translateValue = -(
          this.currentIndex *
          (this.slideWidth + this.gap)
        );

        // Apply transition
        if (animate) {
          this.track.style.transition = "transform 0.5s ease-in-out";
        } else {
          this.track.style.transition = "none";
        }

        this.track.style.transform = `translateX(${translateValue}px)`;

        // Update button states
        this.updateButtons();
      },

      nextSlide: function () {
        // Scroll one card at a time
        const maxIndex = this.slides.length - this.slidesToShow;
        if (this.currentIndex < maxIndex) {
          this.currentIndex++; // Move by 1 card
          this.updatePosition(true);
        }
      },

      prevSlide: function () {
        // Scroll one card at a time
        if (this.currentIndex > 0) {
          this.currentIndex--; // Move by 1 card
          this.updatePosition(true);
        }
      },

      updateButtons: function () {
        const maxIndex = this.slides.length - this.slidesToShow;

        // Disable/enable prev button
        if (this.prevBtn) {
          if (this.currentIndex === 0) {
            this.prevBtn.style.opacity = "0.5";
            this.prevBtn.style.cursor = "not-allowed";
          } else {
            this.prevBtn.style.opacity = "1";
            this.prevBtn.style.cursor = "pointer";
          }
        }

        // Disable/enable next button
        if (this.nextBtn) {
          if (this.currentIndex >= maxIndex) {
            this.nextBtn.style.opacity = "0.5";
            this.nextBtn.style.cursor = "not-allowed";
          } else {
            this.nextBtn.style.opacity = "1";
            this.nextBtn.style.cursor = "pointer";
          }
        }
      },
    };

    // Initialize service carousel
    serviceCarousel.init();

    /**
     * ========================================
     * Mobile Sidebar Menu Functionality
     * ========================================
     */
    const mobileSidebar = {
      sidebar: document.querySelector(".tp-mobile-sidebar"),
      overlay: document.querySelector(".tp-mobile-sidebar-overlay"),
      openBtn: document.querySelector(".tp-mobile-menu-toggle"),
      closeBtn: document.querySelector(".tp-mobile-sidebar-close"),
      menuLinks: document.querySelectorAll(".tp-mobile-menu-link"),

      init: function () {
        if (!this.sidebar || !this.overlay || !this.openBtn || !this.closeBtn)
          return;

        // Open sidebar
        this.openBtn.addEventListener("click", () => this.open());

        // Close sidebar
        this.closeBtn.addEventListener("click", () => this.close());
        this.overlay.addEventListener("click", () => this.close());

        // Close sidebar when clicking menu links
        this.menuLinks.forEach((link) => {
          link.addEventListener("click", () => {
            setTimeout(() => this.close(), 300);
          });
        });

        // Close on ESC key
        document.addEventListener("keydown", (e) => {
          if (e.key === "Escape" && this.sidebar.classList.contains("active")) {
            this.close();
          }
        });

        // Prevent body scroll when sidebar is open
        this.sidebar.addEventListener("transitionend", () => {
          if (this.sidebar.classList.contains("active")) {
            document.body.style.overflow = "hidden";
          } else {
            document.body.style.overflow = "";
          }
        });
      },

      open: function () {
        this.sidebar.classList.add("active");
        this.overlay.classList.add("active");
      },

      close: function () {
        this.sidebar.classList.remove("active");
        this.overlay.classList.remove("active");
      },
    };

    // Initialize mobile sidebar
    mobileSidebar.init();
  }); // End DOMContentLoaded
})();
