/**
 * Google Maps integration for North West Baltimore Business Directory
 * Handles map initialization, markers, and interactive features
 */

let map;
let markers = {};  // Object to store markers by place_id

function initMap() {
    // Initialize the map centered on Baltimore
    map = new google.maps.Map(document.getElementById('business-map'), {
        center: { lat: 39.3086, lng: -76.6869 },
        zoom: 11,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ]
    });

    const bounds = new google.maps.LatLngBounds();
    const service = new google.maps.places.PlacesService(map);

    // Create info window
    const infoWindow = new google.maps.InfoWindow();

    // Process each business
    businesses.forEach(business => {
        // Use Places Service to get location details
        service.getDetails({
            placeId: business.place_id,
            fields: ['geometry', 'formatted_address']
        }, (place, status) => {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                const marker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location,
                    title: business.title,
                    animation: google.maps.Animation.DROP,
                    icon: {
                        url: "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(`
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="none">
                                <path d="M12 21C16 17.5 20 14.5 20 10C20 5.58172 16.4183 2 12 2C7.58172 2 4 5.58172 4 10C4 14.5 8 17.5 12 21Z" fill="#3B82F6" stroke="#1E40AF" stroke-width="2" stroke-linejoin="round"/>
                                <circle cx="12" cy="10" r="3" fill="white"/>
                            </svg>
                        `),
                        anchor: new google.maps.Point(16, 32),
                        scaledSize: new google.maps.Size(32, 32)
                    }
                });

                bounds.extend(place.geometry.location);
                markers[business.place_id] = marker;  // Store marker by place_id

                // Create info window content
                const content = `
                    <div class="p-4 max-w-sm bg-white rounded-lg shadow-sm">
                        ${business.thumbnail ? `
                            <div class="w-full h-32 mb-4 overflow-hidden rounded-lg">
                                <img src="${business.thumbnail}" alt="${business.title}" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                            </div>
                        ` : ''}
                        <div class="space-y-2">
                            <h3 class="font-bold text-lg text-gray-900 leading-tight montserrat">${business.title}</h3>
                            <p class="text-sm text-gray-600 flex items-start">
                                <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="flex-1">${place.formatted_address}</span>
                            </p>
                            <div class="pt-2">
                                <a href="${business.url}" 
                                   class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 montserrat">
                                    View Details
                                    <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                `;

                // Add click listener to marker
                marker.addListener('click', () => {
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);
                });

                // Highlight marker on card hover
                const card = document.querySelector(`[data-place-id="${business.place_id}"]`);
                if (card) {
                    card.addEventListener('mouseenter', () => {
                        marker.setIcon({
                            url: "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(`
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" height="40" fill="none">
                                    <path d="M12 21C16 17.5 20 14.5 20 10C20 5.58172 16.4183 2 12 2C7.58172 2 4 5.58172 4 10C4 14.5 8 17.5 12 21Z" fill="#2563EB" stroke="#1E40AF" stroke-width="2" stroke-linejoin="round"/>
                                    <circle cx="12" cy="10" r="3" fill="white"/>
                                </svg>
                            `),
                            anchor: new google.maps.Point(20, 40),
                            scaledSize: new google.maps.Size(40, 40)
                        });
                        marker.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
                    });

                    card.addEventListener('mouseleave', () => {
                        marker.setIcon({
                            url: "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(`
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="none">
                                    <path d="M12 21C16 17.5 20 14.5 20 10C20 5.58172 16.4183 2 12 2C7.58172 2 4 5.58172 4 10C4 14.5 8 17.5 12 21Z" fill="#3B82F6" stroke="#1E40AF" stroke-width="2" stroke-linejoin="round"/>
                                    <circle cx="12" cy="10" r="3" fill="white"/>
                                </svg>
                            `),
                            anchor: new google.maps.Point(16, 32),
                            scaledSize: new google.maps.Size(32, 32)
                        });
                        marker.setZIndex(undefined);
                    });

                    // Click on card to open info window
                    card.addEventListener('click', () => {
                        map.panTo(marker.getPosition());
                        infoWindow.setContent(content);
                        infoWindow.open(map, marker);
                    });
                }

                // Fit map to markers after last business is processed
                if (Object.keys(markers).length === businesses.length) {
                    map.fitBounds(bounds);
                    if (map.getZoom() > 15) map.setZoom(15);
                }
            }
        });
    });
}
