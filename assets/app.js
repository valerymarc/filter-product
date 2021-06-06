/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import noUiSlider from 'nouislider';
import 'nouislider/dist/nouislider.css';
import { parseInt } from 'core-js/core/number';



console.log("HELLO ET BIENVENUE SUR WEBPACK ENCORE");

const slider = document.getElementById('prix-slider');

if(slider){
    const min = document.getElementById('min');
    const max = document.getElementById('max');
    
    const range = noUiSlider.create(slider, {
        start: [min.value || Math.round(slider.dataset.min), max.value || Math.round(slider.dataset.max)],
        connect: true,
        step: 10,
        range: {
            'min': Math.round(slider.dataset.min),
            'max': Math.round(slider.dataset.max)
        }
    });
  
   
    
    range.on('slide', function(values, handle){
        if(handle === 0){
            min.value = Math.round(values[0]);
        }
        if(handle === 1){
            max.value = Math.round(values[1]);
        }
        console.log(values, handle)
    })
}

