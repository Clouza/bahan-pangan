import './bootstrap';
import Alpine from 'alpinejs';
import React from 'react';
import ReactDOM from 'react-dom/client';
import InteractiveMap from './components/InteractiveMap';
import PriceDisparityMap from './components/PriceDisparityMap';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // Existing Interactive Map
    const mapElement = document.getElementById('interactive-map');
    if (mapElement) {
        const commoditiesData = mapElement.dataset.commodities;
        if (commoditiesData) {
            try {
                const commodities = JSON.parse(commoditiesData);
                if (Array.isArray(commodities)) {
                    const root = ReactDOM.createRoot(mapElement);
                    root.render(<React.StrictMode><InteractiveMap commodities={commodities} /></React.StrictMode>);
                }
            } catch (e) {
                console.error("Failed to parse commodities data:", e);
            }
        }
    }

    // New Price Disparity Map
    const priceMapElement = document.getElementById('price-disparity-map');
    if (priceMapElement) {
        const commodity = priceMapElement.dataset.commodity;
        const date = priceMapElement.dataset.date;
        const average = priceMapElement.dataset.average;
        
        if (commodity) {
             const root = ReactDOM.createRoot(priceMapElement);
             root.render(
                 <React.StrictMode>
                     <PriceDisparityMap commodity={commodity} date={date} averagePrice={average} />
                 </React.StrictMode>
             );
        }
    }
});
