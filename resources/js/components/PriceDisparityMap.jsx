import React, { useState, useEffect } from 'react';
import { MapContainer, TileLayer, GeoJSON, Tooltip } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';

const PriceDisparityMap = ({ commodity, date, averagePrice }) => {
    const [mapData, setMapData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const getDisparityColor = (price) => {
        if (!price) return '#e5e7eb'; // Gray for no data
        const avg = Number(averagePrice);
        if (!avg) return '#e5e7eb';

        const pctDiff = ((price - avg) / avg) * 100;
        
        // Red for expensive (positive deviation), Green for cheap (negative deviation)
        if (pctDiff > 20) return '#7f1d1d'; // Deep Red
        if (pctDiff > 10) return '#b91c1c'; // Red
        if (pctDiff > 5) return '#ef4444'; // Light Red
        if (pctDiff > -5) return '#fca5a5'; // Very Light Red / Neutralish
        
        if (pctDiff < -20) return '#14532d'; // Deep Green
        if (pctDiff < -10) return '#15803d'; // Green
        if (pctDiff < -5) return '#22c55e'; // Light Green
        
        return '#f3f4f6'; // Neutral (within +/- 5%)
    };

    const style = (feature) => {
        return {
            fillColor: getDisparityColor(feature.properties.harga),
            weight: 1,
            opacity: 1,
            color: 'white',
            dashArray: '3',
            fillOpacity: 0.9
        };
    };

    useEffect(() => {
        setLoading(true);
        setError(null);

        // Load GeoJSON
        fetch(`/assets/indonesia-province.json`)
            .then(res => {
                if (!res.ok) throw new Error("Failed to load map topology");
                return res.json();
            })
            .then(geoJson => {
                // Fetch Price Data
                const url = `/api/harga-pangan/${encodeURIComponent(commodity)}?date=${date}`;
                fetch(url)
                    .then(res => {
                        if (!res.ok) throw new Error("Failed to load price data");
                        return res.json();
                    })
                    .then(priceData => {
                        // Deep copy to avoid mutating cache if any
                        const updatedGeoJson = JSON.parse(JSON.stringify(geoJson));
                        
                        updatedGeoJson.features.forEach(feature => {
                            const pName = feature.properties.Propinsi || feature.properties.PROVINSI;
                            // Find matching province (case insensitive)
                            const pData = priceData.find(d => 
                                d.provinsi.toLowerCase().trim() === pName.toLowerCase().trim()
                            );
                            feature.properties.harga = pData ? pData.harga : 0;
                        });

                        setMapData(updatedGeoJson);
                        setLoading(false);
                    })
                    .catch(err => {
                        console.error("Error fetching price data:", err);
                        setError("Gagal memuat data harga.");
                        setLoading(false);
                    });
            })
            .catch(err => {
                console.error("Error loading GeoJSON:", err);
                setError("Gagal memuat peta.");
                setLoading(false);
            });
    }, [commodity, date]);

    if (loading) {
        return (
            <div className="flex items-center justify-center h-full bg-gray-50 text-gray-500 w-full">
                <div className="text-center">
                    <svg className="animate-spin h-8 w-8 text-teal-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memuat Peta...</span>
                </div>
            </div>
        );
    }

    if (error) {
        return <div className="flex items-center justify-center h-full text-red-500">{error}</div>;
    }

    if (!mapData) return null;

    return (
        <MapContainer center={[-2.5, 118]} zoom={5} style={{ height: '100%', width: '100%', background: '#f9fafb' }} zoomControl={false}>
            <TileLayer
                attribution='&copy; OpenStreetMap'
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            />
            <GeoJSON 
                key={`${commodity}-${date}`} // Force re-mount on prop change
                data={mapData} 
                style={style}
                onEachFeature={(feature, layer) => {
                    const pName = feature.properties.Propinsi || feature.properties.PROVINSI;
                    const price = feature.properties.harga;
                    const avg = Number(averagePrice);
                    const diff = price - avg;
                    const pct = avg ? (diff/avg)*100 : 0;
                    
                    // Simple tooltip on hover
                    layer.bindTooltip(`${pName}: Rp ${price ? price.toLocaleString('id-ID') : '-'}`, {
                        permanent: false,
                        direction: 'top'
                    });

                    // Detailed popup on click
                    layer.bindPopup(
                        `<div class="text-sm font-sans">
                            <strong class="text-base block mb-1 border-b pb-1">${pName}</strong>
                            <div class="mt-2">Harga: <strong>Rp ${price ? price.toLocaleString('id-ID') : '-'}</strong></div>
                            <div class="mt-1 ${diff > 0 ? 'text-red-600' : diff < 0 ? 'text-green-600' : 'text-gray-600'}">
                                Selisih: ${diff > 0 ? '+' : ''}Rp ${diff.toLocaleString('id-ID')} (${pct.toFixed(1)}%)
                            </div>
                            <div class="text-xs text-gray-400 mt-2 pt-1 border-t">vs Nasional (Rp ${avg.toLocaleString('id-ID', {maximumFractionDigits: 0})})</div>
                        </div>`
                    );
                }} 
            />
        </MapContainer>
    );
};

export default PriceDisparityMap;