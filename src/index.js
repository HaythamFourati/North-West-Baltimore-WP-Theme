import React from "react"
import ReactDOM from "react-dom/client"

// Import slider functionality
import './slider';

// Initialize React components if needed
if (document.querySelector("#render-react-example-here")) {
  const root = ReactDOM.createRoot(document.querySelector("#render-react-example-here"))
  root.render(<ExampleReactComponent />)
}

// Initialize sliders when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  const sliders = document.querySelectorAll('.business-slider');
  if (sliders.length > 0) {
    import('./slider').then(module => {
      sliders.forEach(slider => module.initBusinessSlider(slider));
    });
  }
});
