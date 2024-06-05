
# Test Task For Muhammad Waheed

Task contains a laravel mvc project with multi language (English / Arabic), project contains one user in user table and thera are two more tables, one for categories and that must get his records first because it is a foreign key in the product table. project contains of four major pages (Login / Home / Category / Product) home page contains two links for Category and Product Pages and each page of Category and product contains four major buttons (Add / Update / Dalete / Navigate to the other page). the only difference between Category Page and Product Page is the Filter button which takes place only in product page.

## Pages Reference
### **Login (صفحة تسجيل الدخول)**
Use it to login using email : `admin@admin.com` password : `password` 
for English page
```http
  http://127.0.0.1:8000/en
```
for Arabic page
```http
  http://127.0.0.1:8000/ar
```
------------------------------
### **Flow of pages**

##### **Pages, Url, and thier order**

| Page | Url     | Order                       |
| :-------- | :------- | :-------------------------------- |
| `login` | `http://127.0.0.1:8000/ar` | **1**|
| `home` | `http://127.0.0.1:8000/home/ar` | **2**|
| `Categories` | `http://127.0.0.1:8000/manageCategory/ar` | **3** |
| `Add Category` | `http://127.0.0.1:8000/add-category/ar` | **4**|
| `Update Category` | `http://127.0.0.1:8000/update-category/ar` | **5**|
| `Delete Category` | `http://127.0.0.1:8000/delete-category/ar` | **6**|
| `Products` | `http://127.0.0.1:8000/manageProducts/ar` | **7**|
| `Add Product` | `http://127.0.0.1:8000/add-product/ar` | **8**|
| `Update Product` | `http://127.0.0.1:8000/update-product/ar` | **9**|
| `Delete Product` | `http://127.0.0.1:8000/delete-product/ar` | **10**|
| `Filter Product` | `http://127.0.0.1:8000/filter-product/en` | **11**|

#### `Use Xampp`

- Use XAMPP for Creating and Using Database.

#### `Migrate Data Base First`

- Use the command `php artisan migrate`.

#### `Seed Database User Table`

- Use the command `php artisan db:seed`.


## Authors
- Mohammed Waheed [@MuhammadWaheed](https://github.com/MuhammadWaheed73780)
