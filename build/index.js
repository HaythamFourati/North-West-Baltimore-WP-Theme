/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/react-dom/client.js":
/*!******************************************!*\
  !*** ./node_modules/react-dom/client.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var m = __webpack_require__(/*! react-dom */ "react-dom");
if (false) {} else {
  var i = m.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED;
  exports.createRoot = function(c, o) {
    i.usingClientEntryPoint = true;
    try {
      return m.createRoot(c, o);
    } finally {
      i.usingClientEntryPoint = false;
    }
  };
  exports.hydrateRoot = function(c, h, o) {
    i.usingClientEntryPoint = true;
    try {
      return m.hydrateRoot(c, h, o);
    } finally {
      i.usingClientEntryPoint = false;
    }
  };
}


/***/ }),

/***/ "./src/slider.js":
/*!***********************!*\
  !*** ./src/slider.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initBusinessSlider: () => (/* binding */ initBusinessSlider)
/* harmony export */ });
function initBusinessSlider(sliderElement) {
  if (!sliderElement) return;
  const track = sliderElement.querySelector('.slider-track');
  const slides = Array.from(track.querySelectorAll('.slide'));
  if (slides.length <= 1) return;
  const actualSlideCount = slides.length - 2; // Subtract cloned slides
  let currentSlide = 1; // Start at first real slide (after clone)
  let autoplayInterval = null;
  let isAutoPlaying = true;
  let isTransitioning = false;

  // Create dots - only create for actual slides (not clones)
  const dotsContainer = sliderElement.parentElement.querySelector('.absolute.bottom-4');
  dotsContainer.innerHTML = '';
  for (let i = 0; i < actualSlideCount; i++) {
    const dot = document.createElement('button');
    dot.className = i === 0 ? 'w-3 h-3 rounded-full bg-white shadow-md transition-all' : 'w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-all';
    dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
    dot.addEventListener('click', () => goToSlide(i + 1));
    dotsContainer.appendChild(dot);
  }
  const dots = Array.from(dotsContainer.children);

  // Setup navigation
  const prevButton = sliderElement.parentElement.querySelector('.slider-nav.prev');
  const nextButton = sliderElement.parentElement.querySelector('.slider-nav.next');
  prevButton.addEventListener('click', () => {
    if (!isTransitioning) {
      stopAutoplay();
      prevSlide();
    }
  });
  nextButton.addEventListener('click', () => {
    if (!isTransitioning) {
      stopAutoplay();
      nextSlide();
    }
  });

  // Add keyboard navigation
  document.addEventListener('keydown', e => {
    if (document.activeElement === prevButton || document.activeElement === nextButton) {
      if (e.key === 'ArrowLeft') {
        e.preventDefault();
        if (!isTransitioning) {
          stopAutoplay();
          prevSlide();
        }
      } else if (e.key === 'ArrowRight') {
        e.preventDefault();
        if (!isTransitioning) {
          stopAutoplay();
          nextSlide();
        }
      }
    }
  });
  function updateSlides(skipTransition = false) {
    isTransitioning = true;
    if (skipTransition) {
      track.style.transition = 'none';
    } else {
      track.style.transition = 'transform 0.5s ease-in-out';
    }

    // Calculate the translation
    const translateX = -currentSlide * 100;
    track.style.transform = `translateX(${translateX}%)`;

    // Update opacity for current and adjacent slides
    slides.forEach((slide, index) => {
      if (index === currentSlide) {
        slide.classList.add('active');
      } else {
        slide.classList.remove('active');
      }
    });

    // Update dots - adjust for cloned slides
    const activeDotIndex = currentSlide - 1;
    dots.forEach((dot, index) => {
      dot.className = index === activeDotIndex ? 'w-3 h-3 rounded-full bg-white shadow-md transition-all' : 'w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-all';
    });
    if (skipTransition) {
      isTransitioning = false;
    } else {
      // Reset transition lock after animation
      setTimeout(() => {
        if (currentSlide === 0) {
          // If we're at the first clone, jump to the last real slide
          currentSlide = actualSlideCount;
          updateSlides(true);
        } else if (currentSlide === slides.length - 1) {
          // If we're at the last clone, jump to the first real slide
          currentSlide = 1;
          updateSlides(true);
        }
        isTransitioning = false;
      }, 500);
    }
  }
  function nextSlide() {
    if (isTransitioning) return;
    currentSlide++;
    updateSlides();
  }
  function prevSlide() {
    if (isTransitioning) return;
    currentSlide--;
    updateSlides();
  }
  function goToSlide(index) {
    if (isTransitioning || currentSlide === index) return;
    stopAutoplay();
    currentSlide = index;
    updateSlides();
  }
  function startAutoplay() {
    isAutoPlaying = true;
    autoplayInterval = setInterval(() => {
      if (!isTransitioning) {
        nextSlide();
      }
    }, 5000);
  }
  function stopAutoplay() {
    isAutoPlaying = false;
    clearInterval(autoplayInterval);
  }

  // Initialize
  track.style.transform = `translateX(-100%)`; // Start at first real slide
  updateSlides();
  startAutoplay();

  // Handle visibility change
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      stopAutoplay();
    } else if (!isTransitioning) {
      startAutoplay();
    }
  });

  // Handle transition end to prevent animation glitches
  track.addEventListener('transitionend', () => {
    isTransitioning = false;
  });
}

// Initialize sliders when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  const sliders = document.querySelectorAll('.business-slider');
  sliders.forEach(slider => initBusinessSlider(slider));
});

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "react-dom":
/*!***************************!*\
  !*** external "ReactDOM" ***!
  \***************************/
/***/ ((module) => {

module.exports = window["ReactDOM"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_dom_client__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-dom/client */ "./node_modules/react-dom/client.js");
/* harmony import */ var _slider__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./slider */ "./src/slider.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);



// Import slider functionality


// Initialize React components if needed

if (document.querySelector("#render-react-example-here")) {
  const root = react_dom_client__WEBPACK_IMPORTED_MODULE_1__.createRoot(document.querySelector("#render-react-example-here"));
  root.render(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(ExampleReactComponent, {}));
}

// Initialize sliders when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  const sliders = document.querySelectorAll('.business-slider');
  if (sliders.length > 0) {
    Promise.resolve(/*! import() */).then(__webpack_require__.bind(__webpack_require__, /*! ./slider */ "./src/slider.js")).then(module => {
      sliders.forEach(slider => module.initBusinessSlider(slider));
    });
  }
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map