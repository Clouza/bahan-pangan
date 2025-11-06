import React, { useState, useEffect } from 'react';
import { MapContainer, TileLayer, GeoJSON, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';

const InteractiveMap = ({ commodities }) => {
    if (!commodities || commodities.length === 0) {
        return <p>Tidak ada data komoditas yang tersedia.</p>;
    }

    const [selectedCommodity, setSelectedCommodity] = useState(commodities[0]);
    const [geojsonData, setGeojsonData] = useState(null);

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

    useEffect(() => {
        fetch(`/api/harga-pangan/${selectedCommodity}`)
            .then(response => response.json())
            .then(data => {
                fetch(`/assets/indonesia-province.json`)
                    .then(response => response.json())
                    .then(geojson => {
                        geojson.features.forEach(feature => {
                            const provinceData = data.find(d => d.provinsi.toLowerCase() === feature.properties.Propinsi.toLowerCase());
                            if (provinceData) {
                                feature.properties.harga = provinceData.harga;
                            } else {
                                feature.properties.harga = 0;
                            }
                        });
                        setGeojsonData(geojson);
                    });
            });
    }, [selectedCommodity]);

    return (
        <div>
            <div className="flex flex-wrap gap-4 items-end mb-4">
                <div className="flex-1 min-w-[200px]">
                    <label htmlFor="commodity-select" className="block text-gray-700 font-semibold mb-2">Pilih Komoditas</label>
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
                </div>
            </div>
            <MapContainer center={[-2.5, 118]} zoom={5} style={{ height: '500px' }}>
                <TileLayer
                    attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                />
                {geojsonData && (
                    <GeoJSON data={geojsonData} style={style} onEachFeature={(feature, layer) => {
                        layer.bindPopup('<strong>Provinsi: </strong>' + feature.properties.Propinsi + '<br/>' + '<strong>Harga: </strong> Rp ' + (feature.properties.harga ? feature.properties.harga.toLocaleString('id-ID') : 'N/A'));
                    }} />
                )}
            </MapContainer>
        </div>
    );
};

export default InteractiveMap;