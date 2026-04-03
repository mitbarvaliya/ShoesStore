import mysql.connector
import matplotlib.pyplot as plt
from datetime import datetime, timedelta
import os

db_config = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'root',
    'password': '',
    'database': 'laravel'
}

output_dir = os.path.join(os.path.dirname(__file__), 'public', 'charts')
os.makedirs(output_dir, exist_ok=True)

def get_db_connection():
    return mysql.connector.connect(**db_config)

def get_sales_data():
    conn = get_db_connection()
    cursor = conn.cursor()
    
    query = """
        SELECT DATE(created_at) as date, COUNT(*) as orders, SUM(total_price) as revenue
        FROM orders
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date
    """
    cursor.execute(query)
    results = cursor.fetchall()
    
    cursor.close()
    conn.close()
    
    return results

def get_user_logins_data():
    conn = get_db_connection()
    cursor = conn.cursor()
    
    query = """
        SELECT DATE(registered_at) as date, COUNT(*) as registrations
        FROM users
        WHERE registered_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY DATE(registered_at)
        ORDER BY date
    """
    cursor.execute(query)
    results = cursor.fetchall()
    
    cursor.close()
    conn.close()
    
    return results

def get_monthly_sales_data():
    conn = get_db_connection()
    cursor = conn.cursor()
    
    query = """
        SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as orders, SUM(total_price) as revenue
        FROM orders
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month
        LIMIT 12
    """
    cursor.execute(query)
    results = cursor.fetchall()
    
    cursor.close()
    conn.close()
    
    return results

def get_top_selling_shoes():
    conn = get_db_connection()
    cursor = conn.cursor()
    
    query = """
        SELECT s.name, SUM(oi.quantity) as total_sold
        FROM order_items oi
        JOIN shoes s ON oi.shoe_id = s.id
        JOIN orders o ON oi.order_id = o.id
        GROUP BY s.id, s.name
        ORDER BY total_sold DESC
        LIMIT 10
    """
    cursor.execute(query)
    results = cursor.fetchall()
    
    cursor.close()
    conn.close()
    
    return results

def generate_sales_chart():
    data = get_sales_data()
    
    if not data:
        print("No sales data found")
        return None
    
    dates = [row[0] for row in data]
    orders = [row[1] for row in data]
    revenues = [float(row[2]) if row[2] else 0 for row in data]
    
    fig, ax1 = plt.subplots(figsize=(12, 6))
    
    ax1.set_xlabel('Date')
    ax1.set_ylabel('Orders', color='tab:blue')
    ax1.bar(dates, orders, color='tab:blue', alpha=0.6, label='Orders')
    ax1.tick_params(axis='y', labelcolor='tab:blue')
    ax1.tick_params(axis='x', rotation=45)
    
    ax2 = ax1.twinx()
    ax2.set_ylabel('Revenue ($)', color='tab:green')
    ax2.plot(dates, revenues, color='tab:green', linewidth=2, marker='o', label='Revenue')
    ax2.tick_params(axis='y', labelcolor='tab:green')
    
    plt.title('Daily Sales - Last 30 Days', fontsize=14, fontweight='bold')
    fig.tight_layout()
    
    filepath = os.path.join(output_dir, 'sales_chart.png')
    plt.savefig(filepath, dpi=150, bbox_inches='tight')
    plt.close()
    
    return filepath

def generate_login_chart():
    data = get_user_logins_data()
    
    if not data:
        print("No login/registration data found")
        return None
    
    dates = [row[0] for row in data]
    registrations = [row[1] for row in data]
    
    fig, ax = plt.subplots(figsize=(12, 6))
    
    bars = ax.bar(range(len(dates)), registrations, color='#6366f1', alpha=0.8)
    ax.set_xticks(range(len(dates)))
    ax.set_xticklabels([str(d) for d in dates], rotation=45, ha='right')
    ax.set_xlabel('Date')
    ax.set_ylabel('New Users')
    ax.set_title('Daily User Registrations - Last 30 Days', fontsize=14, fontweight='bold')
    
    for bar in bars:
        height = bar.get_height()
        ax.text(bar.get_x() + bar.get_width()/2., height,
                f'{int(height)}', ha='center', va='bottom', fontsize=8)
    
    max_val = max(registrations) if registrations else 1
    ax.set_ylim(0, max_val * 1.2)
    
    filepath = os.path.join(output_dir, 'login_chart.png')
    plt.savefig(filepath, dpi=100, bbox_inches='tight')
    plt.close()
    
    return filepath

def generate_monthly_sales_chart():
    data = get_monthly_sales_data()
    
    if not data:
        print("No monthly sales data found")
        return None
    
    months = [row[0] for row in data]
    orders = [row[1] for row in data]
    revenues = [float(row[2]) if row[2] else 0 for row in data]
    
    fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(14, 6))
    
    ax1.bar(months, orders, color='#3b82f6', alpha=0.8)
    ax1.set_title('Monthly Orders')
    ax1.set_xlabel('Month')
    ax1.set_ylabel('Orders')
    ax1.tick_params(axis='x', rotation=45)
    
    ax2.bar(months, revenues, color='#10b981', alpha=0.8)
    ax2.set_title('Monthly Revenue')
    ax2.set_xlabel('Month')
    ax2.set_ylabel('Revenue ($)')
    ax2.tick_params(axis='x', rotation=45)
    
    fig.suptitle('Monthly Sales Overview', fontsize=14, fontweight='bold')
    fig.tight_layout()
    
    filepath = os.path.join(output_dir, 'monthly_sales_chart.png')
    plt.savefig(filepath, dpi=150, bbox_inches='tight')
    plt.close()
    
    return filepath

def generate_top_selling_chart():
    data = get_top_selling_shoes()
    
    if not data:
        print("No top selling data found")
        return None
    
    shoes = [row[0] for row in data]
    sold = [row[1] for row in data]
    
    fig, ax = plt.subplots(figsize=(10, 6))
    
    colors = plt.cm.viridis([i/len(shoes) for i in range(len(shoes))])
    bars = ax.barh(shoes, sold, color=colors)
    ax.set_xlabel('Units Sold')
    ax.set_title('Top 10 Best Selling Shoes', fontsize=14, fontweight='bold')
    ax.invert_yaxis()
    
    for bar, val in zip(bars, sold):
        ax.text(bar.get_width() + 0.5, bar.get_y() + bar.get_height()/2, 
                str(val), va='center', fontsize=9)
    
    fig.tight_layout()
    
    filepath = os.path.join(output_dir, 'top_selling_chart.png')
    plt.savefig(filepath, dpi=150, bbox_inches='tight')
    plt.close()
    
    return filepath

def generate_all_charts():
    print("Generating charts...")
    
    charts = {
        'sales': generate_sales_chart(),
        'logins': generate_login_chart(),
        'monthly_sales': generate_monthly_sales_chart(),
        'top_selling': generate_top_selling_chart()
    }
    
    print("\nGenerated charts:")
    for name, path in charts.items():
        if path:
            print(f"  {name}: {path}")
        else:
            print(f"  {name}: No data available")
    
    return charts

if __name__ == '__main__':
    generate_all_charts()
