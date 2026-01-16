const mysql = require('mysql2/promise');

const dbConfig = {
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'pc_hardware_store',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
};

const pool = mysql.createPool(dbConfig);

async function getProducts(filters = {}) {
    try {
        let query = 'SELECT * FROM products WHERE 1=1';
        const params = [];
        
        if (filters.category) {
            query += ' AND category = ?';
            params.push(filters.category);
        }
        
        if (filters.brand) {
            query += ' AND brand = ?';
            params.push(filters.brand);
        }
        
        if (filters.maxPrice) {
            query += ' AND price <= ?';
            params.push(filters.maxPrice);
        }
        
        if (filters.minPrice) {
            query += ' AND price >= ?';
            params.push(filters.minPrice);
        }
        
        if (filters.sortBy) {
            switch(filters.sortBy) {
                case 'price-asc':
                    query += ' ORDER BY price ASC';
                    break;
                case 'price-desc':
                    query += ' ORDER BY price DESC';
                    break;
                case 'rating':
                    query += ' ORDER BY rating DESC';
                    break;
                case 'name':
                    query += ' ORDER BY name ASC';
                    break;
            }
        }
        
        const [rows] = await pool.execute(query, params);
        return rows;
    } catch (error) {
        console.error('Error fetching products:', error);
        throw error;
    }
}

async function getProductById(id) {
    try {
        const [rows] = await pool.execute(
            'SELECT * FROM products WHERE id = ?',
            [id]
        );
        return rows[0];
    } catch (error) {
        console.error('Error fetching product:', error);
        throw error;
    }
}

async function getCategoriesWithCounts() {
    try {
        const [rows] = await pool.execute(
            'SELECT category, COUNT(*) as count FROM products GROUP BY category'
        );
        return rows;
    } catch (error) {
        console.error('Error fetching categories:', error);
        throw error;
    }
}

async function getBrandsWithCounts() {
    try {
        const [rows] = await pool.execute(
            'SELECT brand, COUNT(*) as count FROM products GROUP BY brand'
        );
        return rows;
    } catch (error) {
        console.error('Error fetching brands:', error);
        throw error;
    }
}

async function addProduct(product) {
    try {
        const [result] = await pool.execute(
            'INSERT INTO products (name, category, brand, price, original_price, rating, image) VALUES (?, ?, ?, ?, ?, ?, ?)',
            [product.name, product.category, product.brand, product.price, product.originalPrice, product.rating, product.image]
        );
        return result.insertId;
    } catch (error) {
        console.error('Error adding product:', error);
        throw error;
    }
}

async function updateProduct(id, product) {
    try {
        const [result] = await pool.execute(
            'UPDATE products SET name = ?, category = ?, brand = ?, price = ?, original_price = ?, rating = ?, image = ? WHERE id = ?',
            [product.name, product.category, product.brand, product.price, product.originalPrice, product.rating, product.image, id]
        );
        return result.affectedRows;
    } catch (error) {
        console.error('Error updating product:', error);
        throw error;
    }
}

async function deleteProduct(id) {
    try {
        const [result] = await pool.execute(
            'DELETE FROM products WHERE id = ?',
            [id]
        );
        return result.affectedRows;
    } catch (error) {
        console.error('Error deleting product:', error);
        throw error;
    }
}

async function searchProducts(searchTerm) {
    try {
        const [rows] = await pool.execute(
            'SELECT * FROM products WHERE name LIKE ? OR brand LIKE ? OR category LIKE ?',
            [`%${searchTerm}%`, `%${searchTerm}%`, `%${searchTerm}%`]
        );
        return rows;
    } catch (error) {
        console.error('Error searching products:', error);
        throw error;
    }
}

module.exports = {
    pool,
    getProducts,
    getProductById,
    getCategoriesWithCounts,
    getBrandsWithCounts,
    addProduct,
    updateProduct,
    deleteProduct,
    searchProducts
};
