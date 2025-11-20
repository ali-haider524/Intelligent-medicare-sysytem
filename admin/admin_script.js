function adminDashboard() {
    return {
        currentPage: 'dashboard',
        notifications: 0,
        stats: {
            totalPatients: 0,
            totalDoctors: 0,
            totalNurses: 0,
            todayAppointments: 0,
            todayRevenue: 0,
            lowStockCount: 0,
            completedAppointments: 0,
            pendingAppointments: 0
        },
        
        currentDate: new Date().toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        }),
        
        currentTime: new Date().toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        }),
        
        get pageTitle() {
            const titles = {
                'dashboard': '📊 Dashboard Overview',
                'appointments': '📅 Appointments Management',
                'patients': '👥 Patient Records',
                'doctors': '👨‍⚕️ Doctor Management',
                'pharmacy': '💊 Pharmacy & Inventory',
                'billing': '💰 Billing & Finance',
                'reports': '📈 Reports & Analytics',
                'settings': '⚙️ System Settings'
            };
            return titles[this.currentPage] || 'Admin Panel';
        },
        
        init() {
            console.log('🚀 Admin Dashboard Initialized');
            this.loadAllData();
            this.startAutoRefresh();
            this.startClock();
        },
        
        startClock() {
            // Update time every second
            setInterval(() => {
                this.currentTime = new Date().toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }, 1000);
        },
        
        changePage(page) {
            this.currentPage = page;
            console.log('📄 Changed to page:', page);
            
            // Load page-specific data
            if (page === 'appointments') {
                this.loadAppointments();
            } else if (page === 'patients') {
                this.loadPatients();
            } else if (page === 'doctors') {
                this.loadDoctors();
            } else if (page === 'pharmacy') {
                this.loadPharmacy();
            }
        },
        
        async loadAllData() {
            console.log('📥 Loading all data...');
            await Promise.all([
                this.loadStats(),
                this.loadAppointments(),
                this.loadRecentActivity()
            ]);
            console.log('✅ All data loaded');
        },
        
        async loadStats() {
            try {
                const response = await fetch('../api/admin_stats.php');
                const data = await response.json();
                
                if (data.success) {
                    this.stats = {
                        ...this.stats,
                        ...data.stats,
                        lowStockCount: data.lowStockCount || 0
                    };
                    
                    // Animate numbers
                    this.animateNumbers();
                    
                    console.log('📊 Stats loaded:', this.stats);
                }
            } catch (error) {
                console.error('❌ Error loading stats:', error);
            }
        },
        
        async loadAppointments() {
            try {
                const response = await fetch('../api/appointments.php?action=get_all');
                const data = await response.json();
                
                if (data.success) {
                    window.appointments = data.appointments || [];
                    console.log('📅 Appointments loaded:', window.appointments.length);
                }
            } catch (error) {
                console.error('❌ Error loading appointments:', error);
            }
        },
        
        async loadPatients() {
            try {
                const response = await fetch('../api/patients.php?action=get_all');
                const data = await response.json();
                
                if (data.success) {
                    window.patients = data.patients || [];
                    console.log('👥 Patients loaded:', window.patients.length);
                }
            } catch (error) {
                console.error('❌ Error loading patients:', error);
            }
        },
        
        async loadDoctors() {
            try {
                const response = await fetch('../api/doctors.php?action=get_all');
                const data = await response.json();
                
                if (data.success) {
                    window.doctors = data.doctors || [];
                    console.log('👨‍⚕️ Doctors loaded:', window.doctors.length);
                }
            } catch (error) {
                console.error('❌ Error loading doctors:', error);
            }
        },
        
        async loadPharmacy() {
            try {
                const response = await fetch('../api/medicines.php?action=get_all');
                const data = await response.json();
                
                if (data.success) {
                    window.medicines = data.medicines || [];
                    console.log('💊 Medicines loaded:', window.medicines.length);
                }
            } catch (error) {
                console.error('❌ Error loading medicines:', error);
            }
        },
        
        async loadRecentActivity() {
            try {
                const response = await fetch('../api/recent_activity.php');
                const data = await response.json();
                
                if (data.success) {
                    window.recentActivity = data.activities || [];
                    this.notifications = data.unreadCount || 0;
                }
            } catch (error) {
                console.error('❌ Error loading recent activity:', error);
            }
        },
        
        animateNumbers() {
            // Animate stat numbers with counting effect
            const duration = 1000;
            const steps = 60;
            const interval = duration / steps;
            
            Object.keys(this.stats).forEach(key => {
                const target = this.stats[key];
                if (typeof target === 'number' && target > 0) {
                    let current = 0;
                    const increment = target / steps;
                    
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        this.stats[key] = Math.floor(current);
                    }, interval);
                }
            });
        },
        
        startAutoRefresh() {
            // Refresh data every 30 seconds
            setInterval(() => {
                console.log('🔄 Auto-refreshing data...');
                this.loadStats();
                this.loadRecentActivity();
            }, 30000);
        },
        
        // Chart initialization
        initCharts() {
            setTimeout(() => {
                this.initRevenueChart();
                this.initAppointmentsChart();
                this.initDepartmentChart();
            }, 500);
        },
        
        initRevenueChart() {
            const ctx = document.getElementById('revenueChart');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
                        borderColor: 'rgb(102, 126, 234)',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '₹' + value.toLocaleString()
                            }
                        }
                    }
                }
            });
        },
        
        initAppointmentsChart() {
            const ctx = document.getElementById('appointmentsChart');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Appointments',
                        data: [45, 52, 48, 65, 58, 42, 38],
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        },
        
        initDepartmentChart() {
            const ctx = document.getElementById('departmentChart');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cardiology', 'Neurology', 'Orthopedics', 'Pediatrics', 'General'],
                    datasets: [{
                        data: [30, 25, 20, 15, 10],
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(251, 191, 36, 0.8)',
                            'rgba(168, 85, 247, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
}

// Initialize charts when dashboard loads
document.addEventListener('alpine:initialized', () => {
    setTimeout(() => {
        const dashboard = Alpine.$data(document.querySelector('[x-data]'));
        if (dashboard && dashboard.initCharts) {
            dashboard.initCharts();
        }
    }, 1000);
});

