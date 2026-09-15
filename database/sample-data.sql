USE ecosprout_db;

INSERT INTO roles (role_name)
VALUES
    ('Customer'),
    ('Staff'),
    ('Administrator');

INSERT INTO categories (
    category_name,
    description
)
VALUES
    (
        'Indoor Plants',
        'Plants suitable for indoor rooms and offices.'
    ),
    (
        'Outdoor Plants',
        'Plants suitable for gardens and outdoor areas.'
    ),
    (
        'Flowering Plants',
        'Decorative plants that produce colourful flowers.'
    ),
    (
        'Herbal Plants',
        'Useful plants grown for cooking and home gardens.'
    ),
    (
        'Succulents',
        'Low-maintenance plants that store water.'
    );

INSERT INTO plants (
    category_id,
    plant_name,
    scientific_name,
    description,
    care_instructions,
    price,
    stock_quantity,
    image_name,
    plant_status
)
VALUES
    (
        1,
        'Monstera Deliciosa',
        'Monstera deliciosa',
        'A tropical indoor plant with large split leaves.',
        'Keep in bright indirect light and water when the topsoil dries.',
        4500.00,
        15,
        'monstera-deliciosa.jpg',
        'Active'
    ),
    (
        1,
        'Peace Lily',
        'Spathiphyllum wallisii',
        'A popular indoor plant with attractive white flowers.',
        'Keep in indirect light and maintain lightly moist soil.',
        3800.00,
        18,
        'peace-lily.jpg',
        'Active'
    ),
    (
        1,
        'Snake Plant',
        'Dracaena trifasciata',
        'A hardy indoor plant that requires limited maintenance.',
        'Allow the soil to dry between watering.',
        3000.00,
        20,
        'snake-plant.jpg',
        'Active'
    ),
    (
        2,
        'Areca Palm',
        'Dypsis lutescens',
        'A decorative palm suitable for covered outdoor spaces.',
        'Provide filtered sunlight and regular watering.',
        6500.00,
        8,
        'areca-palm.jpg',
        'Active'
    ),
    (
        2,
        'Jasmine Plant',
        'Jasminum sambac',
        'A fragrant plant suitable for Sri Lankan home gardens.',
        'Place in sunlight and water regularly without flooding.',
        2200.00,
        14,
        'jasmine.jpg',
        'Active'
    ),
    (
        3,
        'Rose Plant',
        'Rosa',
        'A flowering garden plant available in several colours.',
        'Provide direct sunlight and water near the base.',
        2500.00,
        12,
        'rose.jpg',
        'Active'
    ),
    (
        3,
        'Anthurium',
        'Anthurium andraeanum',
        'A decorative flowering plant with bright heart-shaped flowers.',
        'Keep in bright indirect light and humid conditions.',
        4200.00,
        10,
        'anthurium.jpg',
        'Active'
    ),
    (
        4,
        'Curry Leaf Plant',
        'Murraya koenigii',
        'A useful herbal plant commonly grown in Sri Lankan homes.',
        'Grow in a sunny position and water when the soil becomes dry.',
        1800.00,
        25,
        'curry-leaf.jpg',
        'Active'
    ),
    (
        4,
        'Mint Plant',
        'Mentha',
        'A fast-growing herb suitable for pots and home gardens.',
        'Keep the soil moist and provide partial sunlight.',
        850.00,
        30,
        'mint.jpg',
        'Active'
    ),
    (
        5,
        'Aloe Vera',
        'Aloe barbadensis miller',
        'A low-maintenance succulent suitable for bright locations.',
        'Use well-draining soil and avoid excessive watering.',
        1900.00,
        22,
        'aloe-vera.jpg',
        'Active'
    );

INSERT INTO services (
    service_name,
    description,
    base_price,
    duration_minutes,
    service_status
)
VALUES
    (
        'Plant Care and Maintenance',
        'Routine inspection, watering guidance, pruning and basic care.',
        2500.00,
        60,
        'Available'
    ),
    (
        'Repotting Service',
        'Professional repotting with suitable soil and potting guidance.',
        1500.00,
        45,
        'Available'
    ),
    (
        'Garden Setup and Styling',
        'Planning and arranging plants for homes and small offices.',
        15000.00,
        180,
        'Available'
    ),
    (
        'Plant Consultation',
        'Guidance on plant selection, placement and ongoing care.',
        3000.00,
        60,
        'Available'
    );

INSERT INTO workshops (
    workshop_title,
    description,
    workshop_date,
    start_time,
    location,
    capacity,
    registration_fee,
    workshop_status
)
VALUES
    (
        'Indoor Plant Care',
        'Learn watering, lighting and repotting techniques for indoor plants.',
        '2026-10-10',
        '09:00:00',
        'EcoSprout Nursery, Kegalle',
        20,
        3500.00,
        'Scheduled'
    ),
    (
        'Home Gardening Basics',
        'A beginner workshop about planning and maintaining a home garden.',
        '2026-11-07',
        '09:00:00',
        'EcoSprout Nursery, Kegalle',
        25,
        4000.00,
        'Scheduled'
    ),
    (
        'Natural Pest Control',
        'Learn basic natural methods for handling common garden pests.',
        '2026-12-05',
        '09:00:00',
        'EcoSprout Nursery, Kegalle',
        20,
        3500.00,
        'Scheduled'
    );