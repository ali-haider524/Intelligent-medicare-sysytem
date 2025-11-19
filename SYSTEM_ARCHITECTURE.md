# 🏗️ System Architecture - Intelligent Medicare System

## 📊 Complete System Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    INTELLIGENT MEDICARE SYSTEM                   │
│                     (Production Ready)                           │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                        config.php                                │
│              (Central Configuration File)                        │
│  • Database credentials                                          │
│  • Application settings                                          │
│  • Helper functions                                              │
│  • Security settings                                             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    MySQL Database                                │
│                (intelligent_medicare)                            │
│                                                                   │
│  Tables:                                                          │
│  ├── users (patients, doctors, admins)                          │
│  ├── appointments                                                │
│  ├── doctor_profiles                                             │
│  ├── departments                                                 │
│  ├── medicines                                                   │
│  ├── medical_records                                             │
│  ├── prescriptions                                               │
│  ├── ai_chat_sessions                                            │
│  ├── inventory_alerts                                            │
│  └── billing                                                     │
└─────────────────────────────────────────────────────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│   Frontend   │    │   Backend    │    │     APIs     │
│   (Public)   │    │  (Private)   │    │  (Services)  │
└──────────────┘    └──────────────┘    └──────────────┘
```

---

## 🌐 Frontend Layer (Public Access)

### **1. Main Website (public_website.php)**

```
┌─────────────────────────────────────┐
│      Public Hospital Website         │
├─────────────────────────────────────┤
│ • Home Page                          │
│ • Services Information               │
│ • Doctor Profiles (from DB)         │
│ • Department Listings (from DB)     │
│ • Contact Information                │
│ • AI Chatbot Widget                  │
│ • Patient Registration Form          │
└─────────────────────────────────────┘
         │
         ├─→ Reads: doctors, departments
         ├─→ Writes: new patient registration
         └─→ Uses: api/ai_chat.php, api/register.php
```

### **2. Access Points**

```
index.php → Redirects to public_website.php
ACCESS.html → Visual navigation page
```

---

## 🔐 Backend Layer (Authenticated Access)

### **1. Authentication System**

```
┌─────────────────────────────────────┐
│       pages/login.php                │
├─────────────────────────────────────┤
│ • Validates credentials              │
│ • Creates session                    │
│ • Routes to correct dashboard        │
└─────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│     pages/dashboard.php              │
│     (Router)                         │
├─────────────────────────────────────┤
│ IF role = 'patient'                  │
│   → dashboard_patient.php            │
│ IF role = 'doctor'                   │
│   → dashboard_doctor.php             │
│ IF role = 'admin'                    │
│   → dashboard_admin.php              │
└─────────────────────────────────────┘
```

### **2. Patient Portal (dashboard_patient.php)**

```
┌─────────────────────────────────────┐
│      Patient Dashboard               │
├─────────────────────────────────────┤
│ Features:                            │
│ • View upcoming appointments         │
│ • Book new appointments              │
│ • View medical history               │
│ • Chat with AI assistant             │
├─────────────────────────────────────┤
│ Database Operations:                 │
│ • READ: appointments, doctors        │
│ • WRITE: new appointments            │
│ • UPDATE: profile information        │
├─────────────────────────────────────┤
│ APIs Used:                           │
│ • api/appointments.php               │
│ • api/doctors.php                    │
│ • api/ai_chat.php                    │
└─────────────────────────────────────┘
```

### **3. Doctor Portal (dashboard_doctor.php)**

```
┌─────────────────────────────────────┐
│      Doctor Dashboard                │
├─────────────────────────────────────┤
│ Features:                            │
│ • Today's patient queue              │
│ • Update appointment status          │
│ • View patient details               │
│ • Search medicine inventory          │
│ • Access patient history             │
├─────────────────────────────────────┤
│ Database Operations:                 │
│ • READ: appointments, patients       │
│ • READ: medicines inventory          │
│ • UPDATE: appointment status         │
│ • WRITE: medical records             │
├─────────────────────────────────────┤
│ APIs Used:                           │
│ • api/appointments.php               │
│ • api/medicines.php                  │
└─────────────────────────────────────┘
```

### **4. Admin Panel (dashboard_admin.php)**

```
┌─────────────────────────────────────┐
│      Admin Dashboard                 │
├─────────────────────────────────────┤
│ Features:                            │
│ • System statistics                  │
│ • Medicine inventory management      │
│ • Low stock alerts                   │
│ • Staff management                   │
│ • Financial reports                  │
│ • Department management              │
├─────────────────────────────────────┤
│ Database Operations:                 │
│ • READ: all tables                   │
│ • WRITE: medicines, staff            │
│ • UPDATE: inventory, settings        │
│ • DELETE: old records                │
├─────────────────────────────────────┤
│ APIs Used:                           │
│ • api/medicines.php                  │
│ • api/appointments.php               │
│ • api/doctors.php                    │
└─────────────────────────────────────┘
```

---

## 🔌 API Layer (Services)

### **All APIs Connected to Same Database via config.php**

```
┌─────────────────────────────────────┐
│     api/appointments.php             │
├─────────────────────────────────────┤
│ Actions:                             │
│ • get_upcoming                       │
│ • get_today                          │
│ • book                               │
│ • update_status                      │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│     api/doctors.php                  │
├─────────────────────────────────────┤
│ Actions:                             │
│ • get_all                            │
│ • get_by_department                  │
│ • get_available_slots                │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│     api/medicines.php                │
├─────────────────────────────────────┤
│ Actions:                             │
│ • get_all                            │
│ • get_low_stock                      │
│ • search                             │
│ • update_stock                       │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│     api/ai_chat.php                  │
├─────────────────────────────────────┤
│ Features:                            │
│ • Symptom analysis                   │
│ • Emergency guidance                 │
│ • Doctor recommendations             │
│ • Appointment booking help           │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│     api/register.php                 │
├─────────────────────────────────────┤
│ Actions:                             │
│ • Validate new patient data          │
│ • Create user account                │
│ • Hash password                      │
│ • Store in database                  │
└─────────────────────────────────────┘
```

---

## 🔄 Data Flow Examples

### **Example 1: Patient Books Appointment**

```
1. Patient logs in → pages/login.php
   ↓
2. Validates credentials → Database (users table)
   ↓
3. Creates session → $_SESSION['user_id']
   ↓
4. Redirects to → pages/dashboard_patient.php
   ↓
5. Loads doctors → api/doctors.php → Database (doctor_profiles)
   ↓
6. Patient selects doctor & time
   ↓
7. Submits form → api/appointments.php
   ↓
8. Saves appointment → Database (appointments table)
   ↓
9. Returns confirmation → Shows booking reference
```

### **Example 2: Doctor Updates Patient Status**

```
1. Doctor logs in → pages/login.php
   ↓
2. Goes to dashboard → pages/dashboard_doctor.php
   ↓
3. Loads today's patients → api/appointments.php
   ↓
4. Reads from database → appointments + users tables
   ↓
5. Doctor clicks "Start Consultation"
   ↓
6. AJAX call → api/appointments.php (update_status)
   ↓
7. Updates database → appointments.status = 'in_progress'
   ↓
8. Returns success → UI updates instantly
```

### **Example 3: Admin Checks Inventory**

```
1. Admin logs in → pages/login.php
   ↓
2. Goes to admin panel → pages/dashboard_admin.php
   ↓
3. Clicks inventory tab
   ↓
4. Loads medicines → api/medicines.php
   ↓
5. Reads from database → medicines table
   ↓
6. Checks stock levels
   ↓
7. If low stock → Creates alert in inventory_alerts table
   ↓
8. Displays color-coded list → Red for low stock
```

---

## 🗄️ Database Relationships

```
users
  ├─→ doctor_profiles (one-to-one for doctors)
  ├─→ appointments (one-to-many as patient)
  ├─→ appointments (one-to-many as doctor)
  ├─→ medical_records (one-to-many as patient)
  └─→ ai_chat_sessions (one-to-many)

appointments
  ├─→ users (patient_id)
  ├─→ users (doctor_id)
  ├─→ departments
  ├─→ medical_records (one-to-one)
  └─→ billing (one-to-one)

medical_records
  ├─→ appointments
  ├─→ users (patient)
  ├─→ users (doctor)
  └─→ prescriptions (one-to-many)

prescriptions
  ├─→ medical_records
  └─→ medicines

medicines
  ├─→ prescriptions (one-to-many)
  └─→ inventory_alerts (one-to-many)
```

---

## 🔒 Security Architecture

```
┌─────────────────────────────────────┐
│      Security Layers                 │
├─────────────────────────────────────┤
│ 1. Session Management                │
│    • Secure session cookies          │
│    • Session timeout                 │
│    • Session regeneration            │
│                                      │
│ 2. Authentication                    │
│    • Password hashing (bcrypt)       │
│    • Role-based access control       │
│    • Login attempt limiting          │
│                                      │
│ 3. Database Security                 │
│    • Prepared statements (PDO)       │
│    • SQL injection prevention        │
│    • Input sanitization              │
│                                      │
│ 4. CSRF Protection                   │
│    • Token generation                │
│    • Token validation                │
│                                      │
│ 5. XSS Prevention                    │
│    • Output escaping                 │
│    • HTML sanitization               │
└─────────────────────────────────────┘
```

---

## 🚀 Deployment Architecture

```
Local Development:
┌─────────────────────────────────────┐
│ XAMPP/WAMP                           │
│ ├── Apache (Web Server)             │
│ ├── MySQL (Database)                │
│ └── PHP 8.0+                         │
└─────────────────────────────────────┘

Production (Online):
┌─────────────────────────────────────┐
│ Web Hosting (cPanel)                 │
│ ├── Apache/Nginx                     │
│ ├── MySQL Database                   │
│ ├── PHP 8.0+                         │
│ ├── SSL Certificate (HTTPS)         │
│ └── Backup System                    │
└─────────────────────────────────────┘
```

---

## ✅ System Integration Summary

**Everything is Connected:**

- ✅ Single config.php for all settings
- ✅ One database for entire system
- ✅ All APIs use same connection
- ✅ All dashboards share same data
- ✅ Real-time updates across all parts
- ✅ Consistent user experience
- ✅ Easy to deploy and maintain

**Perfect for:**

- ✅ Final year project
- ✅ Real-world deployment
- ✅ Scalable growth
- ✅ Professional presentation

---

## 🎓 For Your Final Year Project

**Show This Architecture:**

1. Draw this diagram on board/slides
2. Explain data flow
3. Show database relationships
4. Demonstrate real-time updates
5. Explain security measures

**Your system is professionally architected and production-ready!** 🏆
