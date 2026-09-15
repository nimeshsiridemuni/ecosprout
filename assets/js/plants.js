// Plant Mock Data and Filtering Logic
const mockPlants = [
    { id: 1, name: 'Monstera Deliciosa', botanical: 'Monstera deliciosa', category: 'Indoor Plants', price: 4500, stock: 'In Stock', sunlight: 'Partial', image: 'plants/monstera.jpg' },
    { id: 2, name: 'Snake Plant', botanical: 'Sansevieria trifasciata', category: 'Indoor Plants', price: 2500, stock: 'In Stock', sunlight: 'Low', image: 'plants/snake.jpg' },
    { id: 3, name: 'Fiddle Leaf Fig', botanical: 'Ficus lyrata', category: 'Indoor Plants', price: 6000, stock: 'Low Stock', sunlight: 'Bright', image: 'plants/fiddle.jpg' },
    { id: 4, name: 'Areca Palm', botanical: 'Dypsis lutescens', category: 'Outdoor Plants', price: 3500, stock: 'In Stock', sunlight: 'Partial', image: 'plants/areca.jpg' },
    { id: 5, name: 'Bougainvillea', botanical: 'Bougainvillea glabra', category: 'Ornamental Plants', price: 1800, stock: 'In Stock', sunlight: 'Full Sun', image: 'plants/bougainvillea.jpg' },
    { id: 6, name: 'Tomato Plant', botanical: 'Solanum lycopersicum', category: 'Edible Plants', price: 800, stock: 'Out of Stock', sunlight: 'Full Sun', image: 'plants/tomato.jpg' },
    { id: 7, name: 'Chili Plant', botanical: 'Capsicum annuum', category: 'Edible Plants', price: 600, stock: 'In Stock', sunlight: 'Full Sun', image: 'plants/chili.jpg' },
    { id: 8, name: 'Aloe Vera', botanical: 'Aloe barbadensis miller', category: 'Indoor Plants', price: 1200, stock: 'In Stock', sunlight: 'Bright', image: 'plants/aloe.jpg' },
    { id: 9, name: 'Peace Lily', botanical: 'Spathiphyllum', category: 'Indoor Plants', price: 2200, stock: 'In Stock', sunlight: 'Low', image: 'plants/peace.jpg' },
    { id: 10, name: 'Rose Bush', botanical: 'Rosa rubiginosa', category: 'Ornamental Plants', price: 2800, stock: 'Low Stock', sunlight: 'Full Sun', image: 'plants/rose.jpg' },
    { id: 11, name: 'Mint Herb', botanical: 'Mentha', category: 'Edible Plants', price: 500, stock: 'In Stock', sunlight: 'Partial', image: 'plants/mint.jpg' },
    { id: 12, name: 'Hibiscus', botanical: 'Hibiscus rosa-sinensis', category: 'Outdoor Plants', price: 1500, stock: 'In Stock', sunlight: 'Full Sun', image: 'plants/hibiscus.jpg' }
];

function renderPlants(plants) {
    const grid = document.getElementById('plant-grid');
    const count = document.getElementById('result-count');
    if (!grid) return;
    
    grid.innerHTML = '';
    if (count) count.textContent = `Showing ${plants.length} results`;
    
    if (plants.length === 0) {
        grid.innerHTML = '<div class="card text-center" style="grid-column: 1 / -1;"><h3 class="text-secondary">No plants found</h3><p>Try adjusting your filters.</p></div>';
        return;
    }
    
    plants.forEach(plant => {
        const badgeClass = plant.stock === 'In Stock' ? 'badge-green' : (plant.stock === 'Low Stock' ? 'badge-gold' : 'badge-red');
        const html = `
            <div class="card card-interactive">
                <div style="height: 200px; background: #fff; border-radius: 8px; margin-bottom: 15px; display:flex; justify-content:center; align-items:center; color:#000">[Image]</div>
                <div class="flex justify-between align-center mb-10">
                    <span class="badge ${badgeClass}">${plant.stock}</span>
                    <span class="text-secondary font-size-sm">${plant.category}</span>
                </div>
                <h3 style="margin: 0 0 5px 0;">${plant.name}</h3>
                <p class="text-secondary font-size-sm" style="font-style: italic; margin-top:0;">${plant.botanical}</p>
                <div class="flex justify-between align-center mt-20">
                    <span class="text-green font-weight-bold" style="font-size: 1.2rem;">LKR ${plant.price.toLocaleString()}</span>
                    <a href="plant-details.html?id=${plant.id}" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">View</a>
                </div>
            </div>
        `;
        grid.innerHTML += html;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('plant-grid');
    if (!grid) return;
    
    renderPlants(mockPlants);
    
    const searchInput = document.getElementById('search-plant');
    const categorySelect = document.getElementById('filter-category');
    const sunlightSelect = document.getElementById('filter-sunlight');
    const clearBtn = document.getElementById('clear-filters');
    
    function filterPlants() {
        const query = searchInput.value.toLowerCase();
        const category = categorySelect.value;
        const sunlight = sunlightSelect.value;
        
        const filtered = mockPlants.filter(p => {
            const matchQuery = p.name.toLowerCase().includes(query) || p.botanical.toLowerCase().includes(query);
            const matchCat = category === 'All' || p.category === category;
            const matchSun = sunlight === 'All' || p.sunlight === sunlight;
            return matchQuery && matchCat && matchSun;
        });
        renderPlants(filtered);
    }
    
    if (searchInput) searchInput.addEventListener('input', filterPlants);
    if (categorySelect) categorySelect.addEventListener('change', filterPlants);
    if (sunlightSelect) sunlightSelect.addEventListener('change', filterPlants);
    
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            searchInput.value = ''; categorySelect.value = 'All'; sunlightSelect.value = 'All';
            filterPlants();
        });
    }
});
