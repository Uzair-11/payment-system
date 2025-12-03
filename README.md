<div align="center">

# 💳 Payment Gateway Simulation System  
### A full-stack payment system built with PHP, MySQL, HTML, CSS, and JavaScript.

  
⚡ **Admin • Merchant • User • Bank**  
🌑 **Dark Neon Dashboard UI**  
🔐 **Secure Login + Password Hashing**  
🛠 **Built step-by-step with the help of ChatGPT**  
  

</div>

---

## 📌 Overview

This is a **Payment Gateway Simulation System** created for portfolio use.  
It simulates how real payment gateways (Stripe, Razorpay, PayPal) work behind the scenes.

The system includes **four roles**:

- **Admin** – Full system control  
- **Merchant** – Accepts payments  
- **User** – Makes payments  
- **Bank** – Approves or rejects transactions  

The entire project was built step-by-step **with the help of ChatGPT**, including UI, backend logic, security, and workflow design.

---

## 🚀 Features

### 🔐 Authentication
- Role-based login system  
- Admin registration with auto-disable security  
- Password hashing with `password_hash()`  

### 🛠 Admin Panel
- Manage Users (CRUD)  
- Manage Merchants (Verify, Suspend)  
- Manage Banks (PIN, active/inactive)  
- Manage Transactions (Approve, Decline, Filter)  
- Admin profile (change password, edit username)  
- Dark neon UI with sidebar + stats  

### 🛒 Merchant Panel
- View transactions  
- Revenue overview  
- Payout system (coming soon)  
- API key module (upcoming)  

### 👤 User Panel
- Make payments  
- Saved cards (future upgrade)  
- Transaction history  

### 🏦 Bank Panel
- Approve/Decline payments  
- PIN + OTP simulation  
- Transaction queue view  

### 💳 Payment Processing
- Card validation (Luhn algorithm)  
- Tokenization simulation  
- Pending → Approved/Declined  
- Bank approval flow  

---

## 🧱 Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP (XAMPP) |
| Database | MySQL |
| Frontend | HTML, CSS, JavaScript |
| UI Style | Dark Neon + Glassmorphism |
| Version Control | Git + GitHub |

---

## 📂 Folder Structure

/payment-system
│── admin/
│── merchant/
│── user/
│── bank/
│── components/
│── assets/
│── database/
│── index.php
│── README.md


---

## ⚙️ Installation Guide (XAMPP)

### **1. Clone the Repository**

cd C:\xampp\htdocs
git clone https://github.com/Uzair-11/payment-system.git


### **2. Import Database**
- Open **phpMyAdmin**
- Create a database: `payment_system`
- Import the `.sql` file (if included)

### **3. Run the project**
Visit:

http://localhost/payment-system/


---

## 🖼 Screenshots

> Add images here after uploading:


---

## 🏗 Future Improvements
- ✨ Merchant API keys  
- ✨ Refunds (Full + Partial)  
- ✨ Settlement system (T+1 payouts)  
- ✨ Email notifications  
- ✨ 2FA for admin  
- ✨ Payment analytics chart  
- ✨ User card vault  

---

## 🙌 Credits

This project was created by **Uzair**  
with development assistance and guidance from **ChatGPT**.

Special thanks to AI-driven workflow support that helped design:
- UI layout  
- Admin system  
- Banking logic  
- Transaction workflow  
- Database structure  
- Security improvements  

---

## 📜 License
This project is open-source and available under the **MIT License**.  
Feel free to use it for learning or portfolio projects.

---

