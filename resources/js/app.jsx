import './bootstrap';
import Alpine from 'alpinejs';
import React from 'react';
import ReactDOM from 'react-dom/client';
import InteractiveMap from './components/InteractiveMap';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
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
});
