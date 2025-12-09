import React, { useState, useEffect } from 'react';
import { MapContainer, TileLayer, GeoJSON, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';

const InteractiveMap = ({ commodities }) => {
    if (!commodities || commodities.length === 0) {
        return <p>Tidak ada data komoditas yang tersedia.</p>;
    }

    const [selectedCommodity, setSelectedCommodity] = useState(commodities[0]);
    const [displayedCommodity, setDisplayedCommodity] = useState(commodities[0]);
    const [baseGeoJson, setBaseGeoJson] = useState(null);
    const [mapData, setMapData] = useState(null);
    const [loading, setLoading] = useState(false);
    const [refreshKey, setRefreshKey] = useState(0);

    const getColor = (d) => {
        return d > 100000 ? '#800026' :
               d > 50000  ? '#BD0026' :
               d > 20000  ? '#E31A1C' :
               d > 10000  ? '#FC4E2A' :
               d > 5000   ? '#FD8D3C' :
               d > 2000   ? '#FEB24C' :
               d > 1000   ? '#FED976' :
                          '#FFEDA0';
    }

    const style = (feature) => {
        return {
            fillColor: getColor(feature.properties.harga),
            weight: 2,
            opacity: 1,
            color: 'white',
            dashArray: '3',
            fillOpacity: 0.7
        };
    }

    const fetchMapData = (commodity, geoJson) => {
        if (!geoJson) return;

        setLoading(true);
        const encodedCommodity = encodeURIComponent(commodity);
        fetch(`/api/harga-pangan/${encodedCommodity}`)
            .then(response => response.json())
            .then(priceData => {
                console.log(`Data fetched for ${commodity}:`, priceData);
                
                const updatedGeoJson = JSON.parse(JSON.stringify(geoJson));
                let matchCount = 0;

                updatedGeoJson.features.forEach(feature => {
                    const provinceName = feature.properties.Propinsi || feature.properties.PROVINSI;
                    const provinceData = priceData.find(d => d.provinsi.toLowerCase() === provinceName.toLowerCase());
                    
                    if (provinceData) {
                        feature.properties.harga = provinceData.harga;
                        matchCount++;
                    } else {
                        feature.properties.harga = 0;
                    }
                });
                
                console.log(`Matched ${matchCount} provinces for ${commodity}`);

                setMapData(updatedGeoJson);
                setDisplayedCommodity(commodity);
                setRefreshKey(prev => prev + 1); // Ensure map re-renders
                setLoading(false);
            })
            .catch(err => {
                console.error("Error loading price data:", err);
                setLoading(false);
            });
    };

    // Fetch Base GeoJSON once
    useEffect(() => {
        setLoading(true);
        fetch(`/assets/indonesia-province.json`)
            .then(response => response.json())
            .then(data => {
                setBaseGeoJson(data);
                // Initial fetch for the first commodity once GeoJSON is loaded
                fetchMapData(commodities[0], data);
            })
            .catch(err => {
                console.error("Error loading GeoJSON:", err);
                setLoading(false);
            });
    }, []);

    const handleShowMap = () => {
        fetchMapData(selectedCommodity, baseGeoJson);
    };

    return (
        <div>
            <div className="flex flex-wrap gap-4 items-end mb-4">
                <div className="flex-1 min-w-[200px]">
                    <label htmlFor="commodity-select" className="block text-gray-700 font-semibold mb-2">Pilih Komoditas</label>
                    <div className="flex gap-2">
                        <select
                            id="commodity-select"
                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none"
                            value={selectedCommodity}
                            onChange={(e) => setSelectedCommodity(e.target.value)}
                        >
                            {commodities.map(commodity => (
                                <option key={commodity} value={commodity}>{commodity}</option>
                            ))}
                        </select>
                        <button 
                            onClick={handleShowMap}
                            className="px-6 py-2 bg-red-800 text-white font-semibold rounded-lg hover:bg-red-900 transition-colors shadow-md disabled:opacity-50 cursor-pointer flex items-center gap-2"
                            disabled={loading}
                        >
                            {loading && (
                                <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            )}
                            {loading ? 'Memuat...' : 'Tampilkan'}
                        </button>
                    </div>
                </div>
            </div>
            <MapContainer center={[-2.5, 118]} zoom={5} style={{ height: '500px' }}>
                <TileLayer
                    attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                />
                {mapData && (
                    <GeoJSON 
                        key={`${displayedCommodity}-${refreshKey}`} 
                        data={mapData} 
                        style={style} 
                        onEachFeature={(feature, layer) => {
                            const pName = feature.properties.Propinsi || feature.properties.PROVINSI;
                            layer.bindPopup(
                                '<strong>Provinsi: </strong>' + pName + '<br/>' + 
                                '<strong>Harga: </strong> Rp ' + (feature.properties.harga ? feature.properties.harga.toLocaleString('id-ID') : 'N/A')
                            );
                        }} 
                    />
                )}
            </MapContainer>
        </div>
    );
};

export default InteractiveMap;